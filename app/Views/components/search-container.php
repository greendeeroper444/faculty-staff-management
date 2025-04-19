<?php
    //fetch institutes from the controller
    $institutes = $listController->getInstitutes();
?>

<div class="search-container">
    <div class="search-box">
        <div class="search-form">
            <input type="text" class="search-input" placeholder="Enter name">
            
            <select class="institute-select">
                <option value="">All Institutes</option>
                <?php foreach ($institutes as $institute): ?>
                    <option value="<?php echo htmlspecialchars($institute); ?>"><?php echo htmlspecialchars($institute); ?></option>
                <?php endforeach; ?>
                
                <?php if (empty($institutes)): ?>
                    <!-- Fallback options if no institutes are found in database -->
                    <option value="College of Arts and Social Sciences">College of Arts and Social Sciences</option>
                    <option value="College of Engineering">College of Engineering</option>
                    <option value="College of Business">College of Business</option>
                    <option value="College of Science">College of Science</option>
                    <option value="College of Education">College of Education</option>
                <?php endif; ?>
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