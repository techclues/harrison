</main>
<footer class="site-footer">
  <div class="wrap">
    <div class="footer-top">
      <div><a class="footer-brand" href="<?= h($basePath) ?>index.php"><img src="<?= h($basePath) ?>assets/harrison-logo.png" width="674" height="371" alt="Harrison Accountants" loading="lazy"></a><p class="footer-statement">Financial clarity.<br>Fully considered.</p></div>
      <div><p class="footer-label">Explore</p><nav class="footer-links" aria-label="Footer navigation"><a href="<?= h($basePath) ?>index.php">Home</a><a href="<?= h($basePath) ?>services.php">Services</a><a href="<?= h($basePath) ?>who-we-help.php">Who We Help</a><a href="<?= h($basePath) ?>about.php">About</a><a href="<?= h($basePath) ?>contact.php">Contact</a></nav></div>
      <div><p class="footer-label">Services</p><nav class="footer-links footer-links-services" aria-label="Service links"><?php foreach (services() as $navSlug => $navService): ?><a href="<?= h(service_url($navSlug, $basePath)) ?>"><?= h($navService['title']) ?></a><?php endforeach; ?></nav></div>
    </div>
    <div class="footer-bottom"><p>© <?= date('Y') ?> Harrison Accountants. <span>Working draft — content for approval.</span></p><a href="#top">Back to top ↑</a></div>
  </div>
</footer>
<!-- Pin versions before production. These libraries are optional enhancements; navigation works without them. -->
<script src="https://cdn.jsdelivr.net/npm/gsap@3.15.0/dist/gsap.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/gsap@3.15.0/dist/ScrollTrigger.min.js" defer></script>
<script src="https://unpkg.com/lenis@1.3.26/dist/lenis.min.js" defer></script>
<script src="<?= h($basePath) ?>assets/js/site.js" defer></script>
<script src="<?= h($basePath) ?>assets/js/animations.js" defer></script>
<script src="<?= h($basePath) ?>assets/js/typewriter.js" defer></script>
<script src="<?= h($basePath) ?>assets/js/dot-field.js" defer></script>
<script src="<?= h($basePath) ?>assets/js/pin-scroll.js" defer></script>
<script src="<?= h($basePath) ?>assets/js/scroll-lines.js" defer></script>
</body>
</html>
