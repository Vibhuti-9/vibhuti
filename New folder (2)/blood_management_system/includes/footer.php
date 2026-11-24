</div>
<footer style="background-color: #333; color: white; text-align: center; padding: 25px 15px; margin-top: 40px;">
    <div
        style="max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
        <div>
            <p style="margin: 0; font-weight: 600;">🩸 <?php echo $lang_data['title'] ?? 'Blood Management System'; ?>
            </p>
            <p style="margin: 5px 0 0 0; font-size: 0.85rem; color: #aaa;">Saving lives through efficient common and
                rare blood tracking.</p>
        </div>
        <div style="display: flex; gap: 20px; font-size: 0.9rem;">
            <a href="index.php"
                style="color: #ddd; text-decoration: none;"><?php echo $lang_data['home'] ?? 'Home'; ?></a>
            <a href="inventory.php"
                style="color: #ddd; text-decoration: none;"><?php echo $lang_data['inventory'] ?? 'Inventory'; ?></a>
            <a href="requests.php"
                style="color: #ddd; text-decoration: none;"><?php echo $lang_data['requests'] ?? 'Requests'; ?></a>
            <a href="donors.php"
                style="color: #ddd; text-decoration: none;"><?php echo $lang_data['donors'] ?? 'Donors'; ?></a>
        </div>
    </div>
    <div style="margin-top: 20px; border-top: 1px solid #444; padding-top: 15px; font-size: 0.8rem; color: #888;">
        &copy; <?php echo date('Y'); ?> BloodBank System. All rights reserved. Built with Core PHP & MySQL.
    </div>
</footer>
<script src="js/main.js"></script>
</body>

</html>