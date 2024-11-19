/* Enable tooltip triggers with popperjs */
const tooltipTriggerList = document.querySelectorAll(
  '[data-bs-toggle="tooltip"]'
);
const tooltipList = [...tooltipTriggerList].map(
  (tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl)
);

/* Actor */
function previewFile(input, previewId) {
  const preview = document.getElementById(previewId);
  const file = input.files[0];
  const reader = new FileReader();

  reader.onload = function (e) {
    preview.src = e.target.result;
  };

  if (file) {
    reader.readAsDataURL(file);
  } else {
    preview.src = ""; // Clear preview if no file chosen
  }
}

// Function to add an actor input group to a specific container
function addActorToContainer(containerId) {
  const container = document.getElementById(containerId);
  const actorInputGroup = container.querySelector(".actor-input-group");
  const newInputGroup = actorInputGroup.cloneNode(true);

  // Reset values in the new input group
  newInputGroup.querySelector(".actor-select").value = "";
  newInputGroup.querySelector('[name="roles[]"]').value = "";

  // Show "Remove" button on new input groups
  newInputGroup.querySelector(".remove-actor-button").style.display = "block";

  container.appendChild(newInputGroup);
}

// Function to add an actor to the Create Movie section
if (document.getElementById("actorsContainer")) {
  document.addEventListener("DOMContentLoaded", function () {
    const actorsContainer = document.getElementById("actorsContainer");
    const addActorButton = document.getElementById("addActorButton");

    addActorButton.addEventListener("click", () => {
      addActorToContainer("actorsContainer");
    });

    // Event listener for removing an actor input group (Create Movie)
    actorsContainer.addEventListener("click", (event) => {
      if (event.target.classList.contains("remove-actor-button")) {
        event.target.closest(".actor-input-group").remove();
      }
    });
  });
}

// Function to add an actor to the Edit Movie section
function addActorToEdit(containerId) {
  addActorToContainer(containerId);

  // Add event listener for removing actor input group (Edit Movie)
  const container = document.getElementById(containerId);
  container.addEventListener("click", (event) => {
    if (event.target.classList.contains("remove-actor-button")) {
      event.target.closest(".actor-input-group").remove();
    }
  });
}

// Attach event listener to each Edit Movie modal
const editMovieModals = document.querySelectorAll(".editMovie");
editMovieModals.forEach((modal) => {
  modal.addEventListener("shown.bs.modal", () => {
    // Get the movie ID from the modal's ID (you might need to adjust this based on your ID structure)
    const movieId = modal.id.replace("editMovie", "");

    // Attach the "Add Actor" button event listener within the modal
    const addActorButtonEdit = modal.querySelector(
      "#editActorsContainer" + movieId + " .btn-primary"
    );
    if (addActorButtonEdit) {
      addActorButtonEdit.addEventListener("click", () => {
        addActorToEdit("editActorsContainer" + movieId);
      });
    }

    // Select file inputs within the current modal
    const modalFileInputs = modal.querySelectorAll('input[type="file"]');

    modalFileInputs.forEach((input) => {
      input.addEventListener("change", function () {
        const file = this.files[0];
        const fieldName = this.name;

        // Validate file type and size
        const isValidFile = validateFile(file, fieldName);

        // Construct the error div ID using the movieID
        const errorDivId =
          fieldName + "Error" + modal.id.replace("editMovie", "");
        const errorDivEdit = modal.querySelector("#" + errorDivId);

        // Display error message or hide it
        if (!isValidFile) {
          errorDivEdit.textContent = getErrorMessage(file, fieldName);
          errorDivEdit.classList.add("show"); // Add the 'show' class to display the error
          errorDivEdit.classList.remove("hide"); // Remove the 'hide' class if it exists
        } else {
          errorDivEdit.classList.remove("show"); // Remove the 'show' class to hide the error
          errorDivEdit.classList.add("hide"); // Add the 'hide' class
        }

        // Disable/enable buttons within the modal
        const updateButton = modal.querySelector(
          'button[type="submit"][value="update_movie"]'
        );
        updateButton.disabled = !isValidFile;
      });
    });
  });
});

/* File selection */
document.addEventListener("DOMContentLoaded", function () {
  const fileInputs = document.querySelectorAll('input[type="file"]');
  const createButton = document.querySelector('input[value="Create_movie"]');
  const updateButtons = document.querySelectorAll(
    'button[type="submit"][value="update_movie"]'
  );

  fileInputs.forEach((input) => {
    input.addEventListener("change", function () {
      const file = this.files[0]; // Get the selected file
      const fieldName = this.name; // Get the name of the file input field
      const errorDivId = fieldName + "Error"; // Construct the ID of the error div
      const errorDiv = document.getElementById(errorDivId);

      // Validate file type and size
      const isValidFile = validateFile(file, fieldName);

      // Display error message or hide it
      if (!isValidFile) {
        errorDiv.textContent = getErrorMessage(file, fieldName);
        errorDiv.style.display = "block";
      } else {
        errorDiv.style.display = "none";
      }

      // Disable/enable buttons
      createButton.disabled = !isValidFile;
      updateButtons.forEach((button) => (button.disabled = !isValidFile));
    });
  });

  function validateFile(file, fieldName) {
    if (!file) return false; // No file selected

    const allowedExtensions = {
      poster: ["jpg", "jpeg", "png", "pjpeg", "webp"],
      heroimg: ["jpg", "jpeg", "png", "pjpeg", "webp"],
      trailer: ["mp4", "webm", "ogg"],
    };

    const maxSize = {
      poster: 5242880,
      heroimg: 10485760,
      trailer: 52428800,
    };

    const fileSize = file.size;
    const fileExtension = file.name.split(".").pop().toLowerCase();

    // Check file extension and size
    if (
      !allowedExtensions[fieldName].includes(fileExtension) ||
      fileSize > maxSize[fieldName]
    ) {
      return false;
    }

    return true;
  }

  function getErrorMessage(file, fieldName) {
    const allowedExtensions = {
      poster: ["jpg", "jpeg", "png", "pjpeg", "webp"],
      heroimg: ["jpg", "jpeg", "png", "pjpeg", "webp"],
      trailer: ["mp4", "webm", "ogg"],
    };

    const maxSize = {
      poster: 5242880,
      heroimg: 10485760,
      trailer: 52428800,
    };

    const fileSize = file.size;
    const fileExtension = file.name.split(".").pop().toLowerCase();

    if (!allowedExtensions[fieldName].includes(fileExtension)) {
      return `Error: Only ${allowedExtensions[fieldName].join(
        ", "
      )} files are allowed for ${fieldName}.`;
    } else if (fileSize > maxSize[fieldName]) {
      return `Error: File size exceeds the allowed limit (${(
        maxSize[fieldName] /
        (1024 * 1024)
      ).toFixed(2)} MiB) for ${fieldName}.`;
    } else {
      return "Error: Invalid file.";
    }
  }
});

document.addEventListener("DOMContentLoaded", function () {
  document
    .getElementById("psw-check-in")
    .addEventListener("change", function () {
      var passwordField = document.getElementById("password-in");
      if (this.checked) {
        passwordField.type = "text";
        document.getElementById("toggle-password-in").innerHTML =
          '<i class="bi bi-eye-slash-fill"></i>';
      } else {
        passwordField.type = "password";
        document.getElementById("toggle-password-in").innerHTML =
          '<i class="bi bi-eye-fill"></i>';
      }
    });
  document
    .getElementById("psw-check-up")
    .addEventListener("change", function () {
      var passwordField = document.getElementById("password-up");
      if (this.checked) {
        passwordField.type = "text";
        document.getElementById("toggle-password-up").innerHTML =
          '<i class="bi bi-eye-slash-fill"></i>';
      } else {
        passwordField.type = "password";
        document.getElementById("toggle-password-up").innerHTML =
          '<i class="bi bi-eye-fill"></i>';
      }
    });
});
