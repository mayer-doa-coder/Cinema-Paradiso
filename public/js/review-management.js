// Review Management JavaScript
// Handles edit and delete functionality for movie and TV show reviews

/**
 * Show edit review modal
 */
function editReview(movieId, type = 'movie') {
    const endpoint = type === 'tv' ? `/tv/${movieId}/review` : `/movies/${movieId}/review`;
    
    fetch(endpoint, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const review = data.review;
            
            // Create edit modal
            const modalHTML = `
                <div id="editReviewModal" class="overlay overlay-contentscale" style="display: flex !important; align-items: center; justify-content: center;">
                    <div class="login-content" style="max-width: 600px; max-height: 90vh; overflow-y: auto;">
                        <a href="#" class="close" onclick="closeEditReviewModal(); return false;">×</a>
                        <h3 style="color: #4280bf; font-size: 24px; margin-bottom: 20px;">Edit Your Review</h3>
                        <form id="editReviewForm">
                            <div class="row">
                                <label style="color: #abb7c4; margin-bottom: 10px;">
                                    Movie/Show Title:
                                    <span style="color: #ffffff; font-weight: bold;">${review.movie_title}</span>
                                </label>
                            </div>
                            <div class="row">
                                <label style="color: #abb7c4; margin-bottom: 10px;">
                                    Your Rating: <span id="editRatingValue">${review.rating}</span>/10
                                    <input type="range" id="editRating" name="rating" min="1" max="10" step="0.5" value="${review.rating}" 
                                           style="width: 100%; margin-top: 10px;" oninput="document.getElementById('editRatingValue').textContent = this.value">
                                </label>
                            </div>
                            <div class="row">
                                <label style="color: #abb7c4; margin-bottom: 10px;">
                                    <input type="checkbox" id="editWatchedBefore" name="watched_before" ${review.watched_before ? 'checked' : ''}>
                                    <span>I've watched this before</span>
                                </label>
                            </div>
                            <div class="row">
                                <label style="color: #abb7c4; margin-bottom: 10px;">
                                    Your Review:
                                    <textarea id="editReviewText" name="review" rows="6" 
                                              style="width: 100%; padding: 10px; margin-top: 10px; background: #020d18; border: 1px solid #405266; color: #ffffff; border-radius: 3px;"
                                              placeholder="Share your thoughts..." required>${review.review}</textarea>
                                </label>
                            </div>
                            <div class="row" style="margin-top: 20px;">
                                <button type="submit" class="submit" style="background: #4280bf; color: #ffffff; border: none; padding: 12px 30px; border-radius: 3px; cursor: pointer; margin-right: 10px;">
                                    Update Review
                                </button>
                                <button type="button" onclick="closeEditReviewModal()" style="background: #6c757d; color: #ffffff; border: none; padding: 12px 30px; border-radius: 3px; cursor: pointer;">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            `;
            
            // Add modal to body
            document.body.insertAdjacentHTML('beforeend', modalHTML);
            
            // Handle form submission
            document.getElementById('editReviewForm').addEventListener('submit', function(e) {
                e.preventDefault();
                updateReview(movieId, type);
            });
        } else {
            alert(data.message || 'Failed to load review');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while loading the review');
    });
}

/**
 * Update review
 */
function updateReview(movieId, type = 'movie') {
    const endpoint = type === 'tv' ? `/tv/${movieId}/review` : `/movies/${movieId}/review`;
    
    const formData = {
        rating: parseFloat(document.getElementById('editRating').value),
        watched_before: document.getElementById('editWatchedBefore').checked,
        review: document.getElementById('editReviewText').value
    };
    
    fetch(endpoint, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            closeEditReviewModal();
            // Reload the page to show updated review
            location.reload();
        } else {
            alert(data.message || 'Failed to update review');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the review');
    });
}

/**
 * Delete review
 */
function deleteReview(movieId, type = 'movie') {
    if (!confirm('Are you sure you want to delete this review? This action cannot be undone.')) {
        return;
    }
    
    const endpoint = type === 'tv' ? `/tv/${movieId}/review` : `/movies/${movieId}/review`;
    
    fetch(endpoint, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            // Reload the page to reflect changes
            location.reload();
        } else {
            alert(data.message || 'Failed to delete review');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while deleting the review');
    });
}

/**
 * Close edit review modal
 */
function closeEditReviewModal() {
    const modal = document.getElementById('editReviewModal');
    if (modal) {
        modal.remove();
    }
}

/**
 * Show review options after submission
 */
function showReviewOptions(movieId, type = 'movie') {
    // Add edit and delete buttons after review submission
    const reviewActions = `
        <div class="review-actions" style="margin-top: 15px; display: flex; gap: 10px;">
            <button onclick="editReview(${movieId}, '${type}')" 
                    style="background: #4280bf; color: #ffffff; border: none; padding: 10px 20px; border-radius: 3px; cursor: pointer; font-size: 14px;">
                <i class="ion-edit"></i> Edit Review
            </button>
            <button onclick="deleteReview(${movieId}, '${type}')" 
                    style="background: #dc3545; color: #ffffff; border: none; padding: 10px 20px; border-radius: 3px; cursor: pointer; font-size: 14px;">
                <i class="ion-trash-a"></i> Delete Review
            </button>
        </div>
    `;
    
    return reviewActions;
}

// Export functions for use in other scripts
window.editReview = editReview;
window.deleteReview = deleteReview;
window.closeEditReviewModal = closeEditReviewModal;
window.showReviewOptions = showReviewOptions;
