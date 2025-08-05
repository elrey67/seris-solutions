/* Inherit GeneratePress variables with custom fonts */
:root {
  --seris-heading-font: 'Lora', var(--heading-font-family, serif);
  --seris-body-font: 'Roboto Flex', var(--body-font-family, sans-serif);
}

/* Main slider container - equal height slides */
.seris-slider-container {
  position: relative;
  max-width: var(--container-width, 1200px);
  margin: 2rem auto;
  overflow: hidden;
}

/* Slides wrapper - infinite loop preparation */
.seris-slider-wrapper {
  display: flex;
  transition: transform 0.5s ease;
}

/* Individual slide - equal height */
.seris-slide {
  min-width: 100%;
  flex: 1 0 100%;
  display: flex;
  flex-direction: column;
  padding: 0 1rem;
  box-sizing: border-box;
}

/* Image container - fixed aspect ratio */
.seris-slide-image {
  display: block;
  height: 300px; /* Fixed height */
  overflow: hidden;
}

.seris-slide-image img {
  width: 100%;
  height: 100%;
  object-fit: cover; /* Ensures consistent image cropping */
  transition: transform 0.3s ease;
}

.seris-slide-image:hover img {
  transform: scale(1.03);
}

/* Content container - centered text */
.seris-slide-content {
  padding: 1.5rem;
  text-align: center;
  flex-grow: 1;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

/* Typography */
.seris-slide-title {
  font-family: var(--seris-heading-font);
  font-weight: 500;
  font-size: 36px;
  margin: 0 0 0.5rem;
  line-height: 1.3;
}

.seris-slide-title a {
  color: var(--text-color, #333);
  text-decoration: none;
}

.seris-slide-excerpt {
  font-family: var(--seris-body-font);
  font-variation-settings: 'wght' 400, 'slnt' 0;
  font-size: 1rem;
  line-height: 1.6;
  color: var(--light-text-color, #666);
  margin: 0;
}

/* Removed arrows (as requested) */

/* Responsive adjustments */
@media (max-width: 768px) {
  .seris-slide-image {
    height: 200px;
  }
}