<style>
.avatar {
width: 40px;
height: 40px;
border-radius: 50%;
background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
display: flex;
align-items: center;
justify-content: center;
color: white;
font-weight: bold;
font-size: 14px;
}

.card {
box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
border: 1px solid rgba(0, 0, 0, 0.125);
}

.card-header {
background-color: #f8f9fa;
border-bottom: 1px solid rgba(0, 0, 0, 0.125);
}

.table th {
border-top: none;
font-weight: 600;
color: #495057;
background-color: #f8f9fa;
}

.badge {
font-size: 0.75em;
}

.btn-group .btn-check:checked + .btn {
background-color: #0d6efd;
border-color: #0d6efd;
color: white;
}

.navbar-brand {
font-weight: 600;
}

.modal-header.bg-success {
border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}

.form-label {
font-weight: 500;
color: #495057;
}

.border-bottom {
border-bottom: 2px solid #e9ecef !important;
}

/* Responsive adjustments */
@media (max-width: 768px) {
.btn-group {
display: flex;
flex-wrap: wrap;
gap: 0.25rem;
}

.btn-group .btn {
flex: 1;
min-width: auto;
}

.table-responsive {
font-size: 0.875rem;
}

.avatar {
width: 32px;
height: 32px;
font-size: 12px;
}
}

/* Animation for status changes */
.badge {
transition: all 0.3s ease;
}

.table tbody tr {
transition: background-color 0.2s ease;
}

.table tbody tr:hover {
background-color: rgba(0, 0, 0, 0.025);
}

/* Custom button styles */
.btn-success {
background-color: #198754;
border-color: #198754;
}

.btn-info {
background-color: #0dcaf0;
border-color: #0dcaf0;
color: #000;
}

.btn-warning {
background-color: #ffc107;
border-color: #ffc107;
color: #000;
}

/* Loading states */
.btn:disabled {
opacity: 0.6;
cursor: not-allowed;
}

/* Form enhancements */
.form-control:focus,
.form-select:focus {
border-color: #86b7fe;
box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.is-invalid {
border-color: #dc3545;
}

.is-valid {
border-color: #198754;
}

/* Stats cards hover effect */
.card:hover {
transform: translateY(-2px);
transition: transform 0.2s ease;
box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

/* Quick reason buttons */
.btn-outline-secondary.btn-sm {
font-size: 0.75rem;
padding: 0.25rem 0.5rem;
}
</style>
