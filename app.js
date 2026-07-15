let imageList = [];
let currentPage = 1;

// Entry point: Called when the button is clicked or page loads
function request() {
    fetchMoreImages();
}

// Fetches a new page of images from Unsplash
function fetchMoreImages() {
    const URL = `https://api.unsplash.com/search/photos?page=${currentPage}&query=coding&client_id=mAtNQOsuoJISZoQerlUtSfWO0Is2fNJFCoysbNG1_cM`;

    fetch(URL)
        .then(response => response.json())
        .then(data => {
            if (data.results.length > 0) {
                imageList = imageList.concat(data.results); // Add new images
                currentPage++;
                populateCarousel(); // Refresh carousel with all images
            } else {
                console.log("No more images available.");
            }
        })
        .catch(error => console.log("Error fetching images:", error));
}

// Injects images into the Bootstrap carousel
function populateCarousel() {
    const carouselInner = document.getElementById("carousel-inner");
    carouselInner.innerHTML = ""; // Clear existing carousel items

    imageList.forEach((item, index) => {
        const imageUrl = item.urls.full; // Or .full for high res
        const isActive = index === 0 ? "active" : "";

        const carouselItem = `
            <div class="carousel-item ${isActive}">
                <img src="${imageUrl}" class="d-block w-100" alt="Coding image ${index + 1}" style="height: 500px; object-fit: cover;">
            </div>
        `;

        carouselInner.insertAdjacentHTML("beforeend", carouselItem);
    });
}

// Optional: Load images immediately on page load
document.addEventListener("DOMContentLoaded", request);


document.addEventListener("DOMContentLoaded", () => {
    request(); // Load carousel images
    loadCategoryImages(); // Load card images
});

function loadCategoryImages() {
    const cards = document.querySelectorAll(".category-card");

    cards.forEach((card) => {
        const category = card.getAttribute("data-category");
        const imgTag = card.querySelector("img");

        const URL = `https://api.unsplash.com/search/photos?page=1&query=${encodeURIComponent(category)}&client_id=mAtNQOsuoJISZoQerlUtSfWO0Is2fNJFCoysbNG1_cM`;

        fetch(URL)
            .then(response => response.json())
            .then(data => {
                if (data.results.length > 0) {
                    const imageUrl = data.results[0].urls.small;
                    imgTag.src = imageUrl;
                } else {
                    imgTag.src = "fallback.jpg"; // your fallback image
                }
            })
            .catch(error => {
                console.log("Error loading category image:", error);
                imgTag.src = "fallback.jpg"; // fallback if error
            });
    });
}










