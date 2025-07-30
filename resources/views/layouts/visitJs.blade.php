<script>
    

    let currentVisitorId = null
    const bootstrap = window.bootstrap // Declare the bootstrap variable

    // Initialize the page
    document.addEventListener("DOMContentLoaded", () => {
        updateStats()
        setupEventListeners()
    })

    // Setup event listeners
    function setupEventListeners() {
        // Search functionality
        document.getElementById("searchInput").addEventListener("input", filterVisitors)

        // Status filter
        document.querySelectorAll('input[name="statusFilter"]').forEach((radio) => {
            radio.addEventListener("change", filterVisitors)
        })
    }

    // Update statistics
    function updateStats() {
        const stats = {
            total: visitors.length,
            checkedIn: visitors.filter((v) => v.status === "checked-in").length,
            pending: visitors.filter((v) => v.status === "pending").length,
            checkedOut: visitors.filter((v) => v.status === "checked-out").length,
        }

        document.getElementById("totalVisitors").textContent = stats.total
        document.getElementById("checkedInVisitors").textContent = stats.checkedIn
        document.getElementById("pendingVisitors").textContent = stats.pending
        document.getElementById("checkedOutVisitors").textContent = stats.checkedOut
    }

    // Filter visitors based on search and status
    function filterVisitors() {
        const searchTerm = document.getElementById("searchInput").value.toLowerCase()
        const statusFilter = document.querySelector('input[name="statusFilter"]:checked').value

        const rows = document.querySelectorAll("#visitorsTableBody tr")

        rows.forEach((row) => {
            const name = row.querySelector("strong").textContent.toLowerCase()
            const company = row.cells[1].textContent.toLowerCase()
            const host = row.cells[2].textContent.toLowerCase()
            const status = row.getAttribute("data-status")

            const matchesSearch = name.includes(searchTerm) || company.includes(searchTerm) || host.includes(
                searchTerm)
            const matchesStatus = statusFilter === "all" || status === statusFilter

            row.style.display = matchesSearch && matchesStatus ? "" : "none"
        })
    }

    // Check in visitor
    function checkIn(visitorId) {
        const visitor = visitors.find((v) => v.id === visitorId)
        if (visitor) {
            visitor.status = "checked-in"
            visitor.checkInTime = new Date().toLocaleTimeString("en-US", {
                hour: "2-digit",
                minute: "2-digit",
                hour12: true,
            })

            // Update UI
            updateVisitorRow(visitor)
            updateStats()

            // Show success message
            showToast("Visitor checked in successfully!", "success")
        }
    }

    // Check out visitor
    function checkOut(visitorId) {
        const visitor = visitors.find((v) => v.id === visitorId)
        if (visitor) {
            visitor.status = "checked-out"
            visitor.checkOutTime = new Date().toLocaleTimeString("en-US", {
                hour: "2-digit",
                minute: "2-digit",
                hour12: true,
            })

            // Update UI
            updateVisitorRow(visitor)
            updateStats()

            // Show success message
            showToast("Visitor checked out successfully!", "success")
        }
    }

    // Update visitor row in table
    function updateVisitorRow(visitor) {
        const row = document.querySelector(`tr[data-status]`)
        // In a real application, you would update the specific row
        // For this demo, we'll reload the page to show updated data
        setTimeout(() => {
            location.reload()
        }, 1000)
    }

    // View visitor details
    function viewVisitor(visitorId) {
        currentVisitorId = visitorId
        const visitor = visitors.find((v) => v.id === visitorId)

        if (visitor) {
            const modalBody = document.getElementById("visitorModalBody")
            modalBody.innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-muted text-uppercase mb-3">Contact Information</h6>
                    <div class="mb-2">
                        <i class="bi bi-envelope me-2 text-muted"></i>
                        <span>${visitor.email}</span>
                    </div>
                    <div class="mb-2">
                        <i class="bi bi-telephone me-2 text-muted"></i>
                        <span>${visitor.phone}</span>
                    </div>
                    <div class="mb-2">
                        <i class="bi bi-building me-2 text-muted"></i>
                        <span>${visitor.company}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted text-uppercase mb-3">Visit Information</h6>
                    <div class="mb-2">
                        <i class="bi bi-person-check me-2 text-muted"></i>
                        <span>Host: ${visitor.host}</span>
                    </div>
                    <div class="mb-2">
                        <i class="bi bi-file-text me-2 text-muted"></i>
                        <span>${visitor.purpose}</span>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-4">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-box-arrow-in-right me-2 text-success"></i>
                        <div>
                            <small class="text-muted d-block">Check In</small>
                            <span>${visitor.checkInTime || "Not recorded"}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-box-arrow-right me-2 text-danger"></i>
                        <div>
                            <small class="text-muted d-block">Check Out</small>
                            <span>${visitor.checkOutTime || "Not recorded"}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-clock me-2 text-primary"></i>
                        <div>
                            <small class="text-muted d-block">Duration</small>
                            <span>N/A</span>
                        </div>
                    </div>
                </div>
            </div>
            ${
              visitor.transfers && visitor.transfers.length > 0
                ? `
                <hr>
                <h6 class="text-muted text-uppercase mb-3">Transfer History</h6>
                ${visitor.transfers
                  .map(
                    (transfer) => `
                    <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded mb-2">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-arrow-left-right me-2 text-primary"></i>
                            <div>
                                <small class="d-block">${transfer.fromHost} → ${transfer.toHost}</small>
                                <small class="text-muted">${transfer.reason}</small>
                            </div>
                        </div>
                        <small class="text-muted">${transfer.timestamp}</small>
                    </div>
                `,
                  )
                  .join("")}
            `
                : ""
            }
        `

            // Show/hide action buttons based on status
            const checkInBtn = document.getElementById("checkInBtn")
            const checkOutBtn = document.getElementById("checkOutBtn")
            const transferBtn = document.getElementById("transferBtn")

            checkInBtn.style.display = visitor.status === "pending" ? "inline-block" : "none"
            checkOutBtn.style.display = visitor.status === "checked-in" ? "inline-block" : "none"
            transferBtn.style.display =
                visitor.status === "pending" || visitor.status === "checked-in" ? "inline-block" : "none"

            // Setup button event listeners
            checkInBtn.onclick = () => {
                checkIn(visitorId)
                bootstrap.Modal.getInstance(document.getElementById("visitorModal")).hide()
            }

            checkOutBtn.onclick = () => {
                checkOut(visitorId)
                bootstrap.Modal.getInstance(document.getElementById("visitorModal")).hide()
            }

            // Show modal
            new bootstrap.Modal(document.getElementById("visitorModal")).show()
        }
    }

    // Show transfer modal
    function showTransferModal() {
        new bootstrap.Modal(document.getElementById("transferModal")).show()
    }

    // Set quick reason for transfer
    function setReason(reason) {
        document.getElementById("transferReason").value = reason
    }

    // Submit transfer
    function submitTransfer() {
        const newHost = document.getElementById("newHost").value
        const reason = document.getElementById("transferReason").value

        if (!newHost || !reason) {
            showToast("Please fill in all required fields", "error")
            return
        }

        const visitor = visitors.find((v) => v.id === currentVisitorId)
        if (visitor) {
            const transfer = {
                id: Date.now(),
                fromHost: visitor.host,
                toHost: newHost,
                reason: reason,
                timestamp: new Date().toLocaleString(),
            }

            visitor.host = newHost
            visitor.transfers = visitor.transfers || []
            visitor.transfers.push(transfer)

            // Close modals
            bootstrap.Modal.getInstance(document.getElementById("transferModal")).hide()
            bootstrap.Modal.getInstance(document.getElementById("visitorModal")).hide()

            // Update UI
            updateVisitorRow(visitor)
            showToast("Visitor transferred successfully!", "success")

            // Reset form
            document.getElementById("transferForm").reset()
        }
    }

    // Show toast notification
    function showToast(message, type = "info") {
        // Create toast element
        const toast = document.createElement("div")
        toast.className =
            `alert alert-${type === "error" ? "danger" : type === "success" ? "success" : "info"} alert-dismissible fade show position-fixed`
        toast.style.cssText = "top: 20px; right: 20px; z-index: 9999; min-width: 300px;"
        toast.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `

        document.body.appendChild(toast)

        // Auto remove after 3 seconds
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast)
            }
        }, 3000)
    }
</script>
