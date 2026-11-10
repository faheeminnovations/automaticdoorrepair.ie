  </div><!-- .content -->
</div><!-- .main-wrap -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Auto-dismiss alerts after 4s
document.querySelectorAll('.alert-success-ap, .alert-error-ap').forEach(el => {
  setTimeout(() => { el.style.opacity='0'; el.style.transition='opacity .5s'; setTimeout(()=>el.remove(), 500); }, 4000);
});
</script>
</body>
</html>
