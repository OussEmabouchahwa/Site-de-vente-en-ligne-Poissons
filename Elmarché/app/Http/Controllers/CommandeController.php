<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class CommandeController extends Controller
{
    public function create(Produit $produit)
    {
        // Ensure only ACHETEUR can create orders
        if (auth()->user()->profile !== 'ACHETEUR') {
            return redirect()->route('dashboard')->with('error', 'Vous n\'êtes pas autorisé à passer des commandes.');
        }

        return view('commandes.create', compact('produit'));
    }

    public function store(Request $request)
    {
        // Extensive logging for debugging
        \Log::channel('daily')->info('Order Creation Attempt', [
            'request_data' => $request->all(),
            'user_id' => auth()->id(),
            'user_profile' => auth()->user()->profile,
            'is_authenticated' => auth()->check()
        ]);

        // Comprehensive validation with detailed error messages
        $validator = Validator::make($request->all(), [
            'produit_id' => [
                'required', 
                'exists:produits,id',
                function($attribute, $value, $fail) {
                    $produit = Produit::find($value);
                    if (!$produit) {
                        $fail('Le produit sélectionné n\'existe pas.');
                    }
                }
            ],
            'quantite' => [
                'required', 
                'integer', 
                'min:1',
                function($attribute, $value, $fail) use ($request) {
                    $produit = Produit::find($request->produit_id);
                    if ($produit && $value > $produit->qte) {
                        $fail('La quantité demandée dépasse le stock disponible.');
                    }
                }
            ],
            'adresse_livraison' => 'required|string|min:10|max:255',
            'mode_livraison' => 'required|in:domicile,point_relais',
            'mode_payment' => 'required|in:carte,virement',
            'RIP' => 'nullable|required_if:mode_payment,virement|string|max:50'
        ], [
            // Custom validation messages
            'produit_id.required' => 'Un produit doit être sélectionné.',
            'produit_id.exists' => 'Le produit sélectionné n\'existe pas.',
            'quantite.required' => 'La quantité est obligatoire.',
            'quantite.min' => 'La quantité doit être d\'au moins 1.',
            'adresse_livraison.required' => 'L\'adresse de livraison est obligatoire.',
            'adresse_livraison.min' => 'L\'adresse de livraison doit contenir au moins 10 caractères.',
            'mode_livraison.required' => 'Le mode de livraison est obligatoire.',
            'mode_payment.required' => 'Le mode de paiement est obligatoire.',
            'RIP.required_if' => 'Le RIP est requis pour un paiement par virement.'
        ]);

        // If validation fails, log and return with errors
        if ($validator->fails()) {
            \Log::channel('daily')->warning('Order Creation Validation Failed', [
                'errors' => $validator->errors()->toArray(),
                'input_data' => $request->all()
            ]);

            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // Verify user profile
        if (!auth()->check() || auth()->user()->profile !== 'ACHETEUR') {
            \Log::channel('daily')->warning('Order Creation Failed: Invalid Profile', [
                'user_id' => auth()->id(),
                'user_profile' => auth()->check() ? auth()->user()->profile : 'Not Authenticated'
            ]);
            return back()->with('error', 'Vous n\'êtes pas autorisé à passer des commandes.');
        }

        // Find the product
        $produit = Produit::findOrFail($request->produit_id);

        // Create order
        $commande = new Commande();
        $commande->user_id = auth()->id();
        $commande->produit_id = $produit->id;
        $commande->qte = $request->quantite;
        $commande->adresse_livraison = $request->adresse_livraison;
        $commande->mode_livraison = $request->mode_livraison;
        $commande->mode_payment = $request->mode_payment;
        
        // Only save RIP if virement payment method
        if ($request->mode_payment === 'virement') {
            $commande->RIP = $request->RIP;
        }

        $commande->code = 'CMD-' . Str::random(8);
        $commande->etat = 'en_attente';
        
        try {
            // Validate the model before saving
            if (!$commande->validate()) {
                throw new \Exception('Validation du modèle Commande échouée');
            }

            $commande->save();

            // Update product stock
            $produit->qte -= $request->quantite;
            $produit->save();

            \Log::channel('daily')->info('Order Created Successfully', [
                'order_id' => $commande->id,
                'order_code' => $commande->code,
                'user_id' => auth()->id()
            ]);

            return redirect()->route('commandes.index')
                ->with('success', 'Votre commande a été enregistrée avec succès! Numéro de commande: ' . $commande->code);
        } catch (\Exception $e) {
            \Log::channel('daily')->error('Order Creation Failed', [
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'order_data' => $commande->toArray(),
                'validation_errors' => $commande->errors ?? 'No model validation errors'
            ]);

            return back()
                ->with('error', 'Une erreur est survenue lors de la création de la commande: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function index()
    {
        $user = auth()->user();

        // Extensive logging
        \Log::channel('daily')->info('Order Retrieval Debug', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_profile' => $user->profile,
        ]);

        if ($user->profile === 'ACHETEUR') {
            $commandes = Commande::where('user_id', $user->id)
                ->with('produit')
                ->get();

            return view('demandes.index', [
                'commandes' => $commandes,
                'profile' => 'ACHETEUR',
                'debug_info' => [
                    'total_commandes' => $commandes->count(),
                    'user_id' => $user->id
                ]
            ]);
        } elseif ($user->profile === 'VENDEUR') {
            // Fetch ALL commandes related to this user's products
            $commandes = Commande::whereHas('produit', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['produit', 'user'])
            ->get();

            // Log detailed information
            \Log::channel('daily')->info('VENDEUR Commandes Retrieval', [
                'user_id' => $user->id,
                'total_commandes' => $commandes->count(),
                'commande_details' => $commandes->map(function($commande) {
                    return [
                        'id' => $commande->id,
                        'produit_id' => $commande->produit_id,
                        'produit_name' => $commande->produit->nom,
                        'user_id' => $commande->user_id,
                        'status' => $commande->etat
                    ];
                })->toArray()
            ]);

            // Get user's products for additional context
            $userProducts = Produit::where('user_id', $user->id)->get();

            return view('demandes.index', [
                'commandes' => $commandes,
                'profile' => 'VENDEUR',
                'debug_info' => [
                    'total_commandes' => $commandes->count(),
                    'total_products' => $userProducts->count(),
                    'user_id' => $user->id,
                    'product_ids' => $userProducts->pluck('id')->toArray()
                ]
            ]);
        }

        // Redirect or show error if not ACHETEUR or VENDEUR
        return redirect()->back()->with('error', 'Accès non autorisé');
    }

    public function updateStatus(Request $request, Commande $commande)
    {
        $request->validate([
            'status' => 'required|in:accepte,refuse'
        ]);

        $user = auth()->user();
        
        // Debug information
        \Log::info('Update Status Request', [
            'user_id' => $user->id,
            'user_profile' => $user->profile,
            'commande_id' => $commande->id,
            'produit_user_id' => $commande->produit->user_id
        ]);

        // Ensure the current user is the seller of the product
        if ($user->profile !== 'VENDEUR') {
            return back()->with('error', 'Vous devez être un vendeur pour modifier cette commande.');
        }

        // Verify that the product belongs to the current user
        if ($commande->produit->user_id !== $user->id) {
            return back()->with('error', 'Ce produit ne vous appartient pas.');
        }

        // Update order status
        $commande->etat = $request->status;
        $commande->save();

        // If refused, return stock to the product
        if ($request->status === 'refuse') {
            $produit = $commande->produit;
            $produit->qte += $commande->qte;
            $produit->save();

            \Log::info('Order Refused: Stock Returned', [
                'commande_id' => $commande->id,
                'produit_id' => $produit->id,
                'returned_quantity' => $commande->qte
            ]);
        }

        // If accepted, reduce stock again to confirm the order
        if ($request->status === 'accepte') {
            $produit = $commande->produit;
            
            // Ensure we don't reduce stock below zero
            if ($produit->qte >= $commande->qte) {
                $produit->qte -= $commande->qte;
                $produit->save();

                \Log::info('Order Accepted: Stock Reduced', [
                    'commande_id' => $commande->id,
                    'produit_id' => $produit->id,
                    'reduced_quantity' => $commande->qte,
                    'remaining_stock' => $produit->qte
                ]);
            } else {
                // Handle case where stock is insufficient
                \Log::warning('Order Acceptance Failed: Insufficient Stock', [
                    'commande_id' => $commande->id,
                    'produit_id' => $produit->id,
                    'current_stock' => $produit->qte,
                    'requested_quantity' => $commande->qte
                ]);

                // Revert order status
                $commande->etat = 'en_attente';
                $commande->save();

                return back()->with('error', 'Stock insuffisant pour traiter cette commande.');
            }
        }

        return back()->with('success', 'Le statut de la commande a été mis à jour.');
    }
}
