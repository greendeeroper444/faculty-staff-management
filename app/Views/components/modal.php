<?php
    $type = isset($type) ? $type : 'faculty';
?>

<!-- add memmber mdoal -->
<div id="add-faculty-staff-list-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Add New <?php echo ucfirst($type); ?></h3>
            <span class="close-modal" aria-label="Close">&times;</span>
        </div>
        <div class="modal-body">
            <form action="" method="POST" enctype="multipart/form-data" id="add-list-form">

                <?php if ($type == 'faculty' || $type == 'staff'): ?>
                    <div style="background-color: #d8f0e2; padding: 8px 10px; margin-bottom: 10px; border-radius: 3px; font-weight: bold; color: #333333;">
                        Name
                    </div>
                    <div class="form-group" style="margin-left: 20px;">
                        <label for="name">Name <span class="required"></span></label>
                        <input type="text" id="name" name="name" required placeholder="Enter full name">
                    </div>
                    
                    <div class="form-group" style="margin-left: 20px;">
                        <label for="photo">Profile Photo</label>
                        <div id="picked_photo_container">
                            <img id="picked_photo" src="" alt="Select a photo" style="max-width: 100px; max-height: 100px;">
                            <p>Select a photo</p>
                        </div>
                        <input type="file" id="photo" name="photo" accept="image/*" class="file-input">
                        <small>Maximum file size: 2MB. Allowed formats: JPG, JPEG, PNG, GIF</small>
                    </div>

                    <div style="background-color: #d8f0e2; padding: 8px 10px; margin-bottom: 10px; border-radius: 3px; font-weight: bold; color: #333333;">
                        Designation and Email
                    </div>
                    <div class="form-group" style="margin-left: 20px;">
                        <label for="designation">Designation <span class="required"></span></label>
                        <input type="text" id="designation" name="designation" required placeholder="Enter designation">
                    </div>
                    <div class="form-group" style="margin-left: 20px;">
                        <label for="email">Email <span class="required"></span></label>
                        <input type="text" id="email" name="email" required placeholder="rodrigoroaduterte@gmail.com">
                    </div>

                    
                    <div style="background-color: #d8f0e2; padding: 8px 10px; margin-bottom: 10px; border-radius: 3px; font-weight: bold; color: #333333;">
                        <?php if ($type == 'faculty'): ?>
                            Academic rank and insitute
                        <?php else: ?>
                            Position and insitute
                        <?php endif; ?>
                    </div>
                    <div class="form-group" style="margin-left: 20px;">
                        <?php if ($type == 'faculty'): ?>
                            <label for="academic_rank">Academic Rank</label>
                            <select id="academic_rank" name="academic_rank">
                                <option value="">-- Select Academic Rank --</option>
                                <option value="Instructor I">Instructor I</option>
                                <option value="Instructor II">Instructor II</option>
                                <option value="Instructor III">Instructor III</option>
                                <option value="Assistant Professor I">Assistant Professor I</option>
                                <option value="Assistant Professor II">Assistant Professor II</option>
                                <option value="Assistant Professor III">Assistant Professor III</option>
                                <option value="Assistant Professor IV">Assistant Professor IV</option>
                                <option value="Associate Professor I">Associate Professor I</option>
                                <option value="Associate Professor II">Associate Professor II</option>
                                <option value="Associate Professor III">Associate Professor III</option>
                                <option value="Associate Professor IV">Associate Professor IV</option>
                                <option value="Associate Professor V">Associate Professor V</option>
                                <option value="Professor I">Professor I</option>
                                <option value="Professor II">Professor II</option>
                                <option value="Professor III">Professor III</option>
                                <option value="Professor IV">Professor IV</option>
                                <option value="Professor V">Professor V</option>
                                <option value="Professor VI">Professor VI</option>
                                <option value="College/University Professor">College/University Professor</option>
                            </select>
                        <?php else: ?>
                            <label for="academic_rank">Position</label>
                            <input type="text" id="academic_rank" name="academic_rank" placeholder="e.g., Administrative Assistant, Lab Technician">
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group" style="margin-left: 20px;">
                        <label for="institute">Institute</label>
                        <select id="institute" name="institute">
                            <option value="">-- Select Institute --</option>
                            <option value="Institute of Aquatic and Applied Sciences">Institute of Aquatic and Applied Sciences</option>
                            <option value="Institute of Computing">Institute of Computing</option>
                            <option value="Institute of Leadership, Entrepreneurship and Good Governance">Institute of Leadership, Entrepreneurship and Good Governance</option>
                            <option value="Institute of Teacher Education">Institute of Teacher Education</option>
                            <option value="Institute of Advanced Studies">Institute of Advanced Studies</option>
                        </select>
                    </div>

                    
                    <div style="background-color: #d8f0e2; padding: 8px 10px; margin-bottom: 10px; border-radius: 3px; font-weight: bold; color: #333333;">
                        Education
                    </div>
                    <div class="form-group" style="margin-left: 20px;">
                        <label for="education">Education</label>
                        <div id="education-container">
                            <div class="research-title-input">
                                <textarea name="education[]" class="research-title" rows="2" placeholder="Education background and qualifications"></textarea>
                                <button type="button" class="btn btn-sm btn-primary add-more mt-2">+ Add more education</button>
                            </div>
                        </div>
                    </div>
                    
                    <div style="background-color: #d8f0e2; padding: 8px 10px; margin-bottom: 10px; border-radius: 3px; font-weight: bold; color: #333333;">
                        Researchs and Links
                    </div>
                    <div class="form-group" style="margin-left: 20px;">
                        <div id="research-titles-container">
                            <div class="research-title-input">
                                <label for="researc_title">Research Title</label>
                                <textarea name="research_title[]" class="research-title" rows="2" placeholder="Enter research title"></textarea>
                                <label for="researc_link">Research Link</label>
                                <input type="url" name="research_link[]" class="research-link mt-2" placeholder="https://research-profile.example.com">
                                <button type="button" class="btn btn-sm btn-primary add-more mt-2">+ Add more research</button>
                            </div>
                        </div>
                    </div>
                        
                    <div style="background-color: #d8f0e2; padding: 8px 10px; margin-bottom: 10px; border-radius: 3px; font-weight: bold; color: #333333;">
                        Google Scholar Link
                    </div>
                    <div class="form-group" style="margin-left: 20px;">
                        <label for="google_scholar_link">Link</label>
                        <input type="url" id="google_scholar_link" name="google_scholar_link" placeholder="https://scholar.google.com/citations?user=example">
                    </div>

                <?php else: ?>
                    <div style="background-color: #d8f0e2; padding: 8px 10px; margin-bottom: 10px; border-radius: 3px; font-weight: bold; color: #333333;">
                        Office Name
                    </div>
                    <div class="form-group" style="margin-left: 20px;">
                        <label for="office_name">Name <span class="required"></span></label>
                        <input type="text" id="office_name" name="office_name" required placeholder="Enter office name">
                    </div>
                    <div style="background-color: #d8f0e2; padding: 8px 10px; margin-bottom: 10px; border-radius: 3px; font-weight: bold; color: #333333;">
                        About
                    </div>
                    <div class="form-group" style="margin-left: 20px;">
                        <label for="about">About <span class="required"></span></label>
                        <textarea id="about" name="about" rows="4" required placeholder="Enter about"></textarea>
                    </div>
                    <div style="background-color: #d8f0e2; padding: 8px 10px; margin-bottom: 10px; border-radius: 3px; font-weight: bold; color: #333333;">
                        Head
                    </div>
                    <div class="form-group" style="margin-left: 20px;">
                        <label for="head">Head <span class="required"></span></label>
                        <input type="text" id="head" name="head" required placeholder="Enter head">
                    </div>
                    <div style="background-color: #d8f0e2; padding: 8px 10px; margin-bottom: 10px; border-radius: 3px; font-weight: bold; color: #333333;">
                        Contact Number
                    </div>
                    <div class="form-group" style="margin-left: 20px;">
                        <label for="contact_number">Contact Number <span class="required"></span></label>
                        <input type="number" id="contact_number" name="contact_number" required placeholder="Enter contact number">
                    </div>
                    <div style="background-color: #d8f0e2; padding: 8px 10px; margin-bottom: 10px; border-radius: 3px; font-weight: bold; color: #333333;">
                        Email Address
                    </div>
                    <div class="form-group" style="margin-left: 20px;">
                        <label for="email">Email Address <span class="required"></span></label>
                        <input type="text" id="email" name="email" required placeholder="Enter email address">
                    </div>
                <?php endif; ?>


                <input type="hidden" name="add_list" value="1">
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary loading-btn" data-loading-text="Adding...">
                        Add <?php echo ucfirst($type); ?>
                    </button>
                    <button type="button" class="btn btn-secondary cancel-modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- edit memebe modal -->
<div id="edit-faculty-staff-list-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit <?php echo ucfirst($type); ?></h3>
            <span class="close-modal" aria-label="Close">&times;</span>
        </div>
        <div class="modal-body">
            <form action="" method="POST" enctype="multipart/form-data" id="edit-list-form">

                <?php if ($type == 'faculty' || $type == 'staff'): ?>
                    <div style="background-color: #d8f0e2; padding: 8px 10px; margin-bottom: 10px; border-radius: 3px; font-weight: bold; color: #333333;">
                        Name
                    </div>
                    <div class="form-group" style="margin-left: 20px;">
                        <label for="edit_name">Name <span class="required"></span></label>
                        <input type="text" id="edit_name" name="name" required placeholder="Enter full name">
                    </div>
                    
                    <div class="form-group" style="margin-left: 20px;">
                        <label for="edit_photo">Profile Photo</label>
                        <div id="current_photo_container">
                            <img id="current_photo" src="" alt="Current Photo" style="max-width: 100px; max-height: 100px;">
                            <p>Current photo</p>
                        </div>
                        <input type="file" id="edit_photo" name="photo" accept="image/*" class="file-input">
                        <small>Maximum file size: 2MB. Allowed formats: JPG, JPEG, PNG, GIF</small>
                        <input type="hidden" id="existing_photo_path" name="existing_photo_path" value="">
                    </div>

                    <div style="background-color: #d8f0e2; padding: 8px 10px; margin-bottom: 10px; border-radius: 3px; font-weight: bold; color: #333333;">
                        Designation and Email
                    </div>
                    <div class="form-group" style="margin-left: 20px;">
                        <label for="edit_designation">Designation <span class="required"></span></label>
                        <input type="text" id="edit_designation" name="designation" required placeholder="Enter designation">
                    </div>
                    <div class="form-group" style="margin-left: 20px;">
                        <label for="edit_email">Email <span class="required"></span></label>
                        <input type="text" id="edit_email" name="email" required placeholder="rodrigoroaduterte@gmail.com">
                    </div>


                    <div style="background-color: #d8f0e2; padding: 8px 10px; margin-bottom: 10px; border-radius: 3px; font-weight: bold; color: #333333;">
                        <?php if ($type == 'faculty'): ?>
                            Academic rank and insitute
                        <?php else: ?>
                            Position and insitute
                        <?php endif; ?>
                    </div>
                    <div class="form-group" style="margin-left: 20px;">
                        <?php if ($type == 'faculty'): ?>
                            <label for="edit_academic_rank">Academic Rank</label>
                            <select id="edit_academic_rank" name="academic_rank">
                                <option value="">-- Select Academic Rank --</option>
                                <option value="Instructor I">Instructor I</option>
                                <option value="Instructor II">Instructor II</option>
                                <option value="Instructor III">Instructor III</option>
                                <option value="Assistant Professor I">Assistant Professor I</option>
                                <option value="Assistant Professor II">Assistant Professor II</option>
                                <option value="Assistant Professor III">Assistant Professor III</option>
                                <option value="Assistant Professor IV">Assistant Professor IV</option>
                                <option value="Associate Professor I">Associate Professor I</option>
                                <option value="Associate Professor II">Associate Professor II</option>
                                <option value="Associate Professor III">Associate Professor III</option>
                                <option value="Associate Professor IV">Associate Professor IV</option>
                                <option value="Associate Professor V">Associate Professor V</option>
                                <option value="Professor I">Professor I</option>
                                <option value="Professor II">Professor II</option>
                                <option value="Professor III">Professor III</option>
                                <option value="Professor IV">Professor IV</option>
                                <option value="Professor V">Professor V</option>
                                <option value="Professor VI">Professor VI</option>
                                <option value="College/University Professor">College/University Professor</option>
                            </select>
                        <?php else: ?>
                            <label for="edit_academic_rank">Position</label>
                            <input type="text" id="edit_academic_rank" name="academic_rank">
                        <?php endif; ?>
                    </div>
                    <div class="form-group" style="margin-left: 20px;">
                        <label for="edit_institute">Institute</label>
                        <select id="edit_institute" name="institute">
                            <option value="">-- Select Institute --</option>
                            <option value="Institute of Aquatic and Applied Sciences">Institute of Aquatic and Applied Sciences</option>
                            <option value="Institute of Computing">Institute of Computing</option>
                            <option value="Institute of Leadership, Entrepreneurship and Good Governance">Institute of Leadership, Entrepreneurship and Good Governance</option>
                            <option value="Institute of Teacher Education">Institute of Teacher Education</option>
                            <option value="Institute of Advanced Studies">Institute of Advanced Studies</option>
                        </select>
                    </div>


                    <div style="background-color: #d8f0e2; padding: 8px 10px; margin-bottom: 10px; border-radius: 3px; font-weight: bold; color: #333333;">
                        Education
                    </div>
                    <div class="form-group" style="margin-left: 20px;">
                        <label for="edit_education">Education</label>
                        <div id="edit-education-container">
                            <div class="research-title-input">
                                <textarea name="education[]" class="research-title" rows="2" placeholder="Education background and qualifications"></textarea>
                                <button type="button" class="btn btn-sm btn-primary add-more mt-2">+ Add more education</button>
                            </div>
                        </div>
                    </div>
                    
                    <div style="background-color: #d8f0e2; padding: 8px 10px; margin-bottom: 10px; border-radius: 3px; font-weight: bold; color: #333333;">
                        Researchs and Links
                    </div>
                    <div class="form-group" style="margin-left: 20px;">
                        <div id="edit-research-titles-container">
                            <div class="research-title-input">
                                <label for="researc_title">Research Title</label>
                                <textarea name="research_title[]" class="research-title" rows="2" placeholder="Enter research title"></textarea>
                                <label for="researc_link">Research Link</label>
                                <input type="url" name="research_link[]" class="research-link mt-2" placeholder="https://research-profile.example.com">
                                <button type="button" class="btn btn-sm btn-primary add-more mt-2">+ Add more research</button>
                            </div>
                        </div>
                    </div>

                    <div style="background-color: #d8f0e2; padding: 8px 10px; margin-bottom: 10px; border-radius: 3px; font-weight: bold; color: #333333;">
                        Google Scholar Link
                    </div>
                    <div class="form-group" style="margin-left: 20px;">
                        <label for="edit_google_scholar_link">Link</label>
                        <input type="url" id="edit_google_scholar_link" name="google_scholar_link" placeholder="https://scholar.google.com/citations?user=example">
                    </div>
                <?php else: ?>
                    <div style="background-color: #d8f0e2; padding: 8px 10px; margin-bottom: 10px; border-radius: 3px; font-weight: bold; color: #333333;">
                        Office Name
                    </div>
                    <div class="form-group" style="margin-left: 20px;">
                        <label for="edit_office_name">Office Name</label>
                        <input type="text" id="edit_office_name" name="office_name" required placeholder="Enter office name">
                    </div>
                    <div style="background-color: #d8f0e2; padding: 8px 10px; margin-bottom: 10px; border-radius: 3px; font-weight: bold; color: #333333;">
                        About
                    </div>
                    <div class="form-group" style="margin-left: 20px;">
                        <label for="edit_about">About</label>
                        <textarea id="edit_about" name="about" rows="4" required placeholder="Enter about"></textarea>
                    </div>
                    <div style="background-color: #d8f0e2; padding: 8px 10px; margin-bottom: 10px; border-radius: 3px; font-weight: bold; color: #333333;">
                        Head
                    </div>
                    <div class="form-group" style="margin-left: 20px;">
                        <label for="edit_head">Head</label>
                        <input type="text" id="edit_head" name="head" required placeholder="Enter head">
                    </div>
                    <div style="background-color: #d8f0e2; padding: 8px 10px; margin-bottom: 10px; border-radius: 3px; font-weight: bold; color: #333333;">
                        Contact Number
                    </div>
                    <div class="form-group" style="margin-left: 20px;">
                        <label for="edit_contact_number">Contact Number</label>
                        <input type="number" id="edit_contact_number" name="contact_number" required placeholder="Enter contact number">
                    </div>
                    <div style="background-color: #d8f0e2; padding: 8px 10px; margin-bottom: 10px; border-radius: 3px; font-weight: bold; color: #333333;">
                        Email Address
                    </div>
                    <div class="form-group" style="margin-left: 20px;">
                        <label for="edit_email">Email Address</label>
                        <input type="text" id="edit_email" name="email" required placeholder="Enter email address">
                    </div>
                <?php endif; ?>
                
                <input type="hidden" name="update_list" value="1">
                <input type="hidden" id="edit_list_id" name="list_id" value="">
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary loading-btn" data-loading-text="Updating...">
                        Update <?php echo ucfirst($type); ?>
                    </button>
                    <button type="button" class="btn btn-secondary cancel-modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- logout confimation modal -->
<div id="logout-confirm-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Confirm Logout</h3>
            <span class="close-modal" aria-label="Close">&times;</span>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to logout?</p>
            <div class="form-actions">
                <a href="<?php echo BASE_URL; ?>/admin/logout.php" class="btn btn-primary loading-btn" data-loading-text="Logging out...">
                    Yes, Logout
                </a>
                <button type="button" class="btn btn-secondary cancel-modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<!-- delete confimation modal -->
<div id="delete-confirm-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Confirm Delete</h3>
            <span class="close-modal" aria-label="Close">&times;</span>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete this <?php echo $type; ?>?</p>
            <div class="form-actions">
                <a href="#" id="confirm-delete-btn" class="btn btn-danger loading-btn" data-loading-text="Deleting...">Yes, Delete</a>
                <button type="button" class="btn btn-secondary cancel-modal">Cancel</button>
            </div>
        </div>
    </div>
</div>