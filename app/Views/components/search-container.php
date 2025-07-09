<?php
    //fetch institutes from the controller
    $type = $_GET['type'] ?? 'faculty';
    $institutes = $listController->getInstitutes($type);
?>

<div class="search-container">
    <div class="search-box">
        <div class="search-form">
            <input type="text" class="search-input" placeholder="Enter name">
            
            <select class="institute-select">
                <?php if ($type === 'faculty'): ?>
                    <option value="">All Institutes</option>
                <?php else: ?>
                    <option value="">All Offices</option>
                <?php endif; ?>
                <?php foreach ($institutes as $institute): ?>
                    <option value="<?php echo htmlspecialchars($institute); ?>"><?php echo htmlspecialchars($institute); ?></option>
                <?php endforeach; ?>
            </select>
            
            <button class="search-button">Search</button>
        </div>
    </div>
    
    <div class="alphabet-filter">
        <ul>
            <?php
                //generate A-Z alphabet links
                for($i = 65; $i <= 90; $i++) {
                    $letter = chr($i);
                    echo '<li><a href="#" class="letter-link">' . $letter . '</a></li>';
                }
            ?>
        </ul>
    </div>
</div>

<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/search-container.css">