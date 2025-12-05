document.querySelector('meta[property="og:url"]').setAttribute("content", window.location.href);

if (template) {
  document.body.classList.add(template);
// Select all elements with the "gradient-template" class
  const gradientTemplateElements = document.querySelectorAll('.gradient-template');

// Loop through the selected elements and add the dynamic class based on the template variable
  gradientTemplateElements.forEach(function (element) {
    element.classList.add(`gradient-template-${template}`);
  });

// Select all elements with the "gradient-template-background" class
  const gradientTemplateBackgroundElements = document.querySelectorAll('.gradient-template-background');

// Loop through the selected elements and add the dynamic class based on the template variable
  gradientTemplateBackgroundElements.forEach(function (element) {
    element.classList.add(`gradient-template-background-${template}`);
  });

// Select all images with the "templated" class
  const templatedImages = document.querySelectorAll('img.templated');

// Loop through the selected images and update the src attribute using getAttribute and setAttribute
  if (template) {
    templatedImages.forEach(function (img) {
      // Get the original src attribute
      const originalSrc = img.getAttribute('src');

      // Check if the original src contains "./img/" and replace it with "./img-[template]/"
      if (originalSrc && originalSrc.includes('./img/')) {
        const newSrc = originalSrc.replace('./img/', `./img-${template}/`);
        img.setAttribute('src', newSrc);
      }
    });
  }

// Select all elements with the "templated" class that have a background-image
  const templatedDivs = document.querySelectorAll('.templated');

// Loop through the divs and update their background-image style
  templatedDivs.forEach(function (div) {
    const bgImage = div.style.backgroundImage;

    if (bgImage && bgImage.includes('./img/')) {
      const newBgImage = bgImage.replace('./img/', `./img-${template}/`);
      div.style.backgroundImage = newBgImage;
    }
  });
};


document.addEventListener('DOMContentLoaded', function () {
  document.body.classList.add('loaded');
  const form = document.getElementById('registrationForm');
  form.addEventListener('submit', function (event) {
    const fname = document.getElementById('fname').value.trim();
    const tel = document.getElementById('tel').value.trim();
    let valid = true;

    // Clear previous errors
    form.querySelectorAll('.error').forEach(function (element) {
      element.remove();
    });

    // Validate name
    if (fname === '') {
      showError('fname', 'שדה שם מלא הוא חובה');
      valid = false;
    }

    // Validate phone number
    if (!isValidIsraeliPhone(tel)) {
      showError('tel', 'הטלפון לא בפורמט תקני');
      valid = false;
    }

    if (!valid) {
      event.preventDefault(); // Prevent form submission if invalid
    }
  });

  function showError(inputId, message) {
    const input = document.getElementById(inputId);
    const error = document.createElement('div');
    error.className = 'error';
    error.style.color = 'red';
    error.textContent = message;
    input.parentNode.appendChild(error);
  }

  function isValidIsraeliPhone(phone) {
    // Israeli phone number regex (including optional country code)
    const regex = /^(?:(?:(\+972|972)|0)(?:-)?(?:(?:(?:[23489]{1}\d{7})|[5]{1}\d{8})))$/;
    return regex.test(phone);
  }
});
