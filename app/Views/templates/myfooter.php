<!----Footer--->
<footer class="footer">
  <div class="container-fluid">
    <span>© <?= date('Y') ?> MJESHTER FITNESS GYM. All Rights Reserved. | Enterprise Management System v2.0</span>
  </div>
</footer>
<!-- End footer -->

</div> <!-- Close body-wrapper -->
</div> <!-- Close page-wrapper -->
</div> <!-- Close main-wrapper -->

<!-- Scripts -->
<script src="<?=base_url('assets/js/vendor.min.js')?>"></script>
<script src="<?=base_url('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js')?>"></script>
<script src="<?=base_url('assets/libs/simplebar/dist/simplebar.min.js')?>"></script>
<script src="<?=base_url('assets/js/theme/app.init.js')?>"></script>
<script src="<?=base_url('assets/js/theme/theme.js')?>"></script>
<script src="<?=base_url('assets/js/theme/app.min.js')?>"></script>
<script src="<?=base_url('assets/js/theme/sidebarmenu.js')?>"></script>
<script src="<?=base_url('assets/libs/owl.carousel/dist/owl.carousel.min.js')?>"></script>
<script src="<?=base_url('assets/js/plugins/toastr-init.js')?>"></script>

<!-- jQuery & DataTables -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- Active Menu Highlight -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const currentUrl = window.location.href;
    document.querySelectorAll('.sidebar-item a').forEach(link => {
      if (link.href === currentUrl) {
        link.parentElement.classList.add('active');
      }
    });
  });
</script>

</body>
</html>