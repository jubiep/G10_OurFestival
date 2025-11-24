const feedbackForm = document.getElementById('feedback-form');
const recentForms = document.getElementById('feedback-list');

// Handle form submission
document.addEventListener('DOMContentLoaded', function() {
    if (feedbackForm) {
        feedbackForm.addEventListener('submit', handleFormSubmit);
    }
    
    // Load and display recent feedback on page load
    // Check first if have feedback-list then do this
    if (recentForms) {
        loadRecentFeedback();
    }
});

// Function to handle form submission
async function handleFormSubmit(event) {
    event.preventDefault();
    
    // Collect form data
    const formData = {
        gender: document.getElementById('feedback-gender').value || 'Not Prefer to Say',
        age: document.getElementById('feedback-age').value || 'N/A',
        booth1Rating: getSelectedRadioValue('feedback-booth-1-rating'),
        booth2Rating: getSelectedRadioValue('feedback-booth-2-rating'),
        booth3Rating: getSelectedRadioValue('feedback-booth-3-rating'),
        booth4Rating: getSelectedRadioValue('feedback-booth-4-rating'),
        favoriteBooth: document.getElementById('feedback-favorite-booth').value || '0',
        comment: document.getElementById('feedback-additional-comment').value || 'No comment',
        timestamp: new Date().toISOString()
    };
    
    try {
        // Send data to PHP endpoint to save to json file
        const response = await fetch('./PHPFiles/save_feedback.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData)
        });
        
        const result = await response.json();
        
        // for tester, this not work correctly in Safari.
        if (result.success) {
            // Reset form normally
            alert('Your submission has been saved.');
            feedbackForm.reset();
            // loadRecentFeedback();
        } else if (result.error){
            alert('Error saving feedback: ' + result.error);
        }

    } catch (error) {
        console.error('error:', error);
        alert('Something went wrong.');
    }
}

// Helper function to get selected radio button value
function getSelectedRadioValue(name) {
    const radios = document.querySelectorAll(`input[name="${name}"]`);
    for (const radio of radios) {
        if (radio.checked) {
            return radio.value;
        }
    }
    return 'N/A';
}

// Function to fetch and display recent feedback
async function loadRecentFeedback() {
    const feedbackList = document.getElementById('feedback-list');
    if (feedbackList) feedbackList.classList.add('row');
    
    if (!feedbackList) {
        return;
    }
    
    try {
        // Fetch list of feedback files
        const response = await fetch('./PHPFiles/get_feedback.php');
        const result = await response.json();
        
        if (result.success && result.feedback.length > 0) {
            // Display feedback entries
            feedbackList.innerHTML = '';
            
            // Sort by timestamp (most recent first)
            const sortedFeedback = result.feedback.sort((a, b) => {
                return new Date(b.timestamp) - new Date(a.timestamp);
            });
            
            // Old I want to display only 10 newer
            const recentFeedback = sortedFeedback;
            
            recentFeedback.forEach((feedback, index) => {
                const feedbackCard = createFeedbackCard(feedback, index + 1);
                feedbackList.appendChild(feedbackCard);
            });
        } else {
            feedbackList.innerHTML = '<p>No feedback submitted yet. Try to be first? This is a chance!</p>';
        }
    } catch (error) {
        console.error('Error loading feedback:', error);
        feedbackList.innerHTML = '<p>Error loading feedback.</p>';
    }
}

// Function to create a feedback card element
function createFeedbackCard(feedback, index) {
    // Create column wrapper
    const col = document.createElement('div');
    col.className = 'primary-field col-md-6 col-lg-4 mb-2'; // Grid classes

    const card = document.createElement('div');
    card.className = 'feedback-card p-3 rounded h-100';
    
    const boothNames = {
        '0': 'None',
        '1': 'Beverages',
        '2': 'Desserts',
        '3': 'Photobooth',
        '4': 'Gadgets'
    };

    // Get name of booth (for favorite) in order.
    const favoriteBoothText = boothNames[feedback.favoriteBooth] || 'None';
    const date = new Date(feedback.timestamp);
    const formattedDate = date.toLocaleString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
    
    // HTML card <<<
    card.innerHTML = `
        <div class="d-flex justify-content-between align-items-start mb-2">
            <h4 class="mb-0">#${index}</h4>
            <small class="text-muted"><p>${escapeHtml(formattedDate)}</p></small>
        </div>
        <div class="row mb-2">
            <div class="col-12">
                <p class="mb-1"><strong>Gender:</strong> ${escapeHtml(feedback.gender)}</p>
                <p class="mb-1"><strong>Age:</strong> ${escapeHtml(feedback.age)}</p>
                <p class="mb-1"><strong>Favorite Booth:</strong> ${escapeHtml(favoriteBoothText)}</p>
            </div>
        </div>
        <div class="mb-2">
            <p><strong>Booth Ratings:</strong></p>
            <div class="ms-3">
                <p class="mb-0">Beverages: ${escapeHtml(String(feedback.booth1Rating))}/5</p>
                <p class="mb-0">Desserts: ${escapeHtml(String(feedback.booth2Rating))}/5</p>
                <p class="mb-0">Photobooth: ${escapeHtml(String(feedback.booth3Rating))}/5</p>
                <p class="mb-0">Gadgets: ${escapeHtml(String(feedback.booth4Rating))}/5</p>
            </div>
        </div>
        ${feedback.comment && feedback.comment !== 'No comment' ? 
            `<div class="mt-2">
                <p><strong>Comment:</strong></p>
                <p class="mb-0 ms-2">${escapeHtml(feedback.comment)}</p>
            </div>` : ''
        }
    `;
    
    col.appendChild(card);
    return col;
}

// Helper function to escape HTML to prevent XSS
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
