class PublicationManager {
  constructor() {
    this.table = null;
    this.init();
  }

  init() {
    // Initialize publication table
    this.table = $("#publicationsTable").DataTable({
      // Configuration moved from inline script
    });

    this.setupEventListeners();
    this.setupFilters();
    this.setupExport();
  }

  setupEventListeners() {
    // Add publication
    $("#savePublication").on("click", () => this.handleSavePublication());

    // Delete publication
    $(".btn-delete-pub").on("click", (e) => this.handleDeletePublication(e));

    // View publication
    $(".btn-view-pub").on("click", (e) => this.handleViewPublication(e));
  }

  handleSavePublication() {
    const form = document.getElementById("addPublicationForm");
    if (!form.checkValidity()) {
      form.reportValidity();
      return;
    }

    // Show loading state
    this.showLoading();

    // Simulate API call
    setTimeout(() => {
      this.hideLoading();
      this.showNotification("Publication saved successfully!", "success");
      $("#addPublicationModal").modal("hide");
      form.reset();
    }, 1000);
  }

  handleDeletePublication(e) {
    e.preventDefault();
    const pubId = e.currentTarget.dataset.id;

    if (confirm("Are you sure you want to delete this publication?")) {
      // Show loading state
      this.showLoading();

      // Simulate API call
      setTimeout(() => {
        this.hideLoading();
        this.showNotification("Publication deleted successfully!", "success");
        // Remove row from table
        this.table.row($(e.currentTarget).closest("tr")).remove().draw();
      }, 1000);
    }
  }

  showLoading() {
    $(".loading-overlay").addClass("active");
  }

  hideLoading() {
    $(".loading-overlay").removeClass("active");
  }

  showNotification(message, type = "info") {
    const notification = $(`
            <div class="notification notification-${type}">
                <i class="fas fa-${
                  type === "success" ? "check-circle" : "info-circle"
                }"></i>
                ${message}
            </div>
        `);

    $(".notification-container").append(notification);
    setTimeout(() => notification.remove(), 3000);
  }
}

// Initialize when document is ready
$(document).ready(() => {
  window.publicationManager = new PublicationManager();
});
