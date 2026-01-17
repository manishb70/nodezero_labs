<?php 
// 1. Disable Browser Protection for the Lab
header("X-XSS-Protection: 0");

// 2. Handle Flag Fetching (AJAX)
if (isset($_GET['fetch_flag'])) {
    echo "<div class='alert-box'>
            <strong>SUCCESS!</strong><br>
            Flag: <code>NodeZero{hidden_promo_code_xss}</code>
          </div>";
    exit;
}

// 3. Get Inputs
$search = $_GET['search'] ?? '';
$cat    = $_GET['cat'] ?? 'all';
$sort   = $_GET['sort'] ?? 'newest';
$promo  = $_GET['promo_code'] ?? ''; // <--- VULNERABLE

// 4. Sanitize (Simulate partial security)
$safe_search = htmlspecialchars($search);
$safe_cat    = htmlspecialchars($cat);
// We intentionally do NOT sanitize $promo
?>

<?php include 'header.php'; ?>

<div class="container">
    <h2>Our Solutions</h2>
    <p style="margin-bottom: 2rem; color: #64748b;">Browse our catalog of enterprise software.</p>

    <form action="" method="GET" class="filters">
        <div class="form-row">
            <div class="form-group" style="flex: 2;">
                <label>Search Keywords</label>
                <input type="text" name="search" value="<?php echo $safe_search; ?>" placeholder="e.g. Analytics">
            </div>
            
            <div class="form-group">
                <label>Category</label>
                <select name="cat">
                    <option value="all">All Industries</option>
                    <option value="finance" <?php if($cat=='finance') echo 'selected'; ?>>Finance</option>
                    <option value="health" <?php if($cat=='health') echo 'selected'; ?>>Healthcare</option>
                </select>
            </div>

            <div class="form-group">
                <label>Sort By</label>
                <select name="sort">
                    <option value="newest">Newest First</option>
                    <option value="price_asc">Price: Low to High</option>
                </select>
            </div>

            <button type="submit" class="btn-search">Filter</button>
        </div>
        
        <div style="margin-top: 1rem; border-top: 1px solid #e2e8f0; padding-top: 1rem;">
            <label style="font-size: 0.8rem; color: #94a3b8;">Have a promo code?</label>
            <input type="text" name="promo_code" value="<?php echo $promo; ?>" placeholder="Optional Code" style="padding: 0.4rem; border: 1px dashed #cbd5e1; width: 200px;">
        </div>
    </form>

    <?php if($promo): ?>
        <div style="background: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af; padding: 1rem; border-radius: 6px; margin-bottom: 2rem;">
            Applied Promo Code: <strong><?php echo $promo; ?></strong>
            <span style="float:right; font-size:0.8em; color:#60a5fa;">(Invalid or Expired)</span>
        </div>
    <?php endif; ?>

    <div class="grid">
        <div class="card">
            <div class="card-img" style="background:linear-gradient(45deg, #1e293b, #334155);">Nexus Analytics v2</div>
            <div class="card-body">
                <h3>Enterprise Analytics</h3>
                <p style="color:#64748b; font-size:0.9rem;">Real-time data processing.</p>
                <span class="price">$499/mo</span>
            </div>
        </div>
        <div class="card">
            <div class="card-img" style="background:linear-gradient(45deg, #2563eb, #3b82f6);">Cloud Shield</div>
            <div class="card-body">
                <h3>Cloud Shield Pro</h3>
                <p style="color:#64748b; font-size:0.9rem;">Advanced firewall protection.</p>
                <span class="price">$299/mo</span>
            </div>
        </div>
        <div class="card">
            <div class="card-img" style="background:linear-gradient(45deg, #059669, #10b981);">Green Server</div>
            <div class="card-body">
                <h3>Eco Hosting</h3>
                <p style="color:#64748b; font-size:0.9rem;">Carbon-neutral server clusters.</p>
                <span class="price">$150/mo</span>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>