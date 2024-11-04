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
const editMovieModals = document.querySelectorAll(".editMovie"); // Select all modals with the class
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
  });
});
