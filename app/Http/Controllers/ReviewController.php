<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }

    /**
     * Afficher les avis d'un produit
     */
    public function index(Product $product)
    {
        $reviews = $product->reviews()
            ->approved()
            ->with('user')
            ->popular()
            ->paginate(10);

        return response()->json([
            'reviews' => $reviews,
            'average_rating' => $product->average_rating,
            'total_reviews' => $product->reviews_count
        ]);
    }

    /**
     * Créer un nouvel avis
     */
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        // Vérifier que l'utilisateur n'a pas déjà donné un avis pour ce produit
        $existingReview = Review::where('product_id', $product->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Vous avez déjà donné un avis pour ce produit.');
        }

        Review::create([
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
            'approved' => true,
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Votre avis a été publié avec succès.');
    }

    /**
     * Voter pour un avis utile
     */
    public function vote(Review $review)
    {
        $review->increment('helpful_votes');
        
        return response()->json([
            'success' => true,
            'helpful_votes' => $review->helpful_votes
        ]);
    }
}
