    </div> <!-- End Main Content Container -->

<!-- FOOTER -->
<footer>
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-4 text-start">
                <h6 class="fw-bold mb-2">
                    <i class="fas fa-pills me-2"></i>Pharmacy Inventory System
                </h6>
                <p class="small text-white-50 m-0">
                    Efficient inventory management for pharmacies
                </p>
            </div>
            <div class="col-md-4 text-center">
                <p class="small mb-0">
                    &copy; <span id="year"></span> PHIRSTLADY Pharmacy. All rights reserved.
                </p>
            </div>
            <div class="col-md-4 text-end">
                <div class="footer-links">
                    <a href="#" title="Privacy Policy">Privacy</a>
                    <a href="#" title="Terms of Service">Terms</a>
                    <a href="#" title="Contact Support">Support</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- SCRIPTS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/script.js"></script>

<script>
// Set current year in footer
document.getElementById('year').textContent = new Date().getFullYear();
</script>

</body>
</html>
