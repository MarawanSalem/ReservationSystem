import './bootstrap';

// Helper function to get user initials
window.getUserInitials = function(name) {
    return name
        .split(' ')
        .map(word => word[0])
        .join('')
        .toUpperCase();
}

// Function to create avatar with initials
window.createUserAvatar = function(element, name) {
    const initials = getUserInitials(name);
    const fontSize = element.clientWidth * 0.4 + 'px';

    element.classList.add('user-avatar');
    element.style.fontSize = fontSize;

    // Clear any existing content
    element.innerHTML = '';

    // Create initials span
    const span = document.createElement('span');
    span.className = 'user-avatar-initials';
    span.textContent = initials;
    span.style.fontSize = fontSize;

    element.appendChild(span);
}

// Initialize avatars on page load
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('img.user-avatar-auto').forEach(img => {
        img.addEventListener('error', function() {
            createUserAvatar(this, this.getAttribute('data-name'));
        });
    });
});
