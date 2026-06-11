    </main>
    <footer class="site-footer">
        <div class="container footer-grid">
            <section>
                <a class="brand footer-brand" href="index.php"><span>Zeta</span>Style</a>
                <p>Premium custom printed clothing designed for polished teams, creators, families, and everyday wardrobes.</p>
                <div class="social-row">
                    <?php foreach ($socialLinks as $link): ?>
                        <a href="<?= e($link['href']); ?>" target="_blank" rel="noopener"><?= e($link['label']); ?></a>
                    <?php endforeach; ?>
                </div>
            </section>
            <section>
                <h2>Quick Links</h2>
                <a href="shop.php">Shop</a>
                <a href="blog.php">Journal</a>
                <a href="about.php">About</a>
                <a href="contact.php">Contact</a>
            </section>
            <section>
                <h2>Customer Support</h2>
                <a href="track-order.php">Track Order</a>
                <a href="category.php?category=men">Men Collection</a>
                <a href="category.php?category=women">Women Collection</a>
                <a href="category.php?category=kids">Kids Collection</a>
            </section>
            <section>
                <h2>Newsletter</h2>
                <p>Receive fabric drops, print-care notes, and private collection previews.</p>
                <form class="newsletter-form">
                    <label class="sr-only" for="newsletter-email">Email address</label>
                    <input id="newsletter-email" type="email" placeholder="Email address" required>
                    <button class="btn btn-gold" type="submit">Join</button>
                </form>
            </section>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?= date('Y'); ?> ZetaStyle. All rights reserved.</p>
        </div>
    </footer>
    <div class="toast" data-toast role="status" aria-live="polite"></div>
    <script src="<?= asset('js/app.js'); ?>" defer></script>
    <script src="<?= asset('js/menu.js'); ?>" defer></script>
    <script src="<?= asset('js/slider.js'); ?>" defer></script>
    <script src="<?= asset('js/cart.js'); ?>" defer></script>
</body>
</html>
