<?php
    $type = isset($type) ? $type : 'faculty';
?>

<!-- add member modal -->
<div id="add-member-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Add New <?php echo ucfirst($type); ?></h3>
            <span class="close-modal">&times;</span>
        </div>
        <div class="modal-body">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Name <span class="required">*</span></label>
                    <input type="text" id="name" name="name" required>
                </div>
                
                <div class="form-group">
                    <label for="photo">Photo</label>
                    <input type="file" id="photo" name="photo" accept="image/*">
                    <small>Maximum file size: 2MB. Allowed formats: JPG, JPEG, PNG, GIF</small>
                </div>
                
                <?php if ($type === 'faculty'): ?>
                    <div class="form-group">
                        <label for="academic_rank">Academic Rank</label>
                        <input type="text" id="academic_rank" name="academic_rank">
                    </div>
                    
                    <div class="form-group">
                        <label for="institute">Institute</label>
                        <input type="text" id="institute" name="institute">
                    </div>
                    
                    <div class="form-group">
                        <label for="education">Education</label>
                        <textarea id="education" name="education" rows="3"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="research_title">Research Titles</label>
                        <div id="research-titles-container">
                            <div class="research-title-input">
                                <textarea name="research_title[]" class="research-title" rows="2"></textarea>
                                <button type="button" class="btn btn-sm btn-primary add-research-title">+ Add another</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="research_link">Research Link</label>
                        <input type="url" id="research_link" name="research_link">
                    </div>
                    
                    <div class="form-group">
                        <label for="google_scholar_link">Google Scholar Link</label>
                        <input type="url" id="google_scholar_link" name="google_scholar_link">
                    </div>
                <?php else: ?>
                    <div class="form-group">
                        <label for="position">Position</label>
                        <input type="text" id="position" name="position">
                    </div>
                    
                    <div class="form-group">
                        <label for="institute">Institute</label>
                        <input type="text" id="institute" name="institute">
                    </div>
                    
                    <div class="form-group">
                        <label for="education">Education</label>
                        <textarea id="education" name="education" rows="3"></textarea>
                    </div>
                <?php endif; ?>
                
                <input type="hidden" name="add_member" value="1">
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Add <?php echo ucfirst($type); ?></button>
                    <button type="button"  class="btn btn-secondary cancel-modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- edit member modal -->
<div id="edit-member-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit <?php echo ucfirst($type); ?></h3>
            <span class="close-modal">&times;</span>
        </div>
        <div class="modal-body">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="edit_name">Name <span class="required">*</span></label>
                    <input type="text" id="edit_name" name="name" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_photo">Photo</label>
                    <div id="current_photo_container" style="margin-bottom: 10px; display: none;">
                        <img id="current_photo" src="" alt="Current Photo" style="max-width: 100px; max-height: 100px;">
                        <p>Current photo</p>
                    </div>
                    <input type="file" id="edit_photo" name="photo" accept="image/*">
                    <small>Maximum file size: 2MB. Allowed formats: JPG, JPEG, PNG, GIF</small>
                    <input type="hidden" id="existing_photo_path" name="existing_photo_path" value="">
                </div>
                
                <?php if ($type === 'faculty'): ?>
                    <div class="form-group">
                        <label for="edit_academic_rank">Academic Rank</label>
                        <input type="text" id="edit_academic_rank" name="academic_rank">
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_institute">Institute</label>
                        <input type="text" id="edit_institute" name="institute">
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_education">Education</label>
                        <textarea id="edit_education" name="education" rows="3"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_research_title">Research Titles</label>
                        <div id="edit-research-titles-container">
                            <div class="research-title-input">
                                <textarea name="research_title[]" class="research-title" rows="2"></textarea>
                                <button type="button" class="btn btn-sm btn-primary add-research-title">+ Add another</button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_research_link">Research Link</label>
                        <input type="url" id="edit_research_link" name="research_link">
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_google_scholar_link">Google Scholar Link</label>
                        <input type="url" id="edit_google_scholar_link" name="google_scholar_link">
                    </div>
                <?php else: ?>
                    <div class="form-group">
                        <label for="edit_position">Position</label>
                        <input type="text" id="edit_position" name="position">
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_institute">Institute</label>
                        <input type="text" id="edit_institute" name="institute">
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_education">Education</label>
                        <textarea id="edit_education" name="education" rows="3"></textarea>
                    </div>
                <?php endif; ?>
                
                <input type="hidden" name="update_member" value="1">
                <input type="hidden" id="edit_member_id" name="member_id" value="">
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Update <?php echo ucfirst($type); ?></button>
                    <button type="button" class="btn btn-secondary cancel-modals">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- logout confirmation modal -->
<div id="logout-confirm-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Confirm Logout</h3>
            <span class="close-modal">&times;</span>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to logout?</p>
            <div class="form-actions">
                <a href="<?php echo BASE_URL; ?>/admin/logout.php" class="btn btn-primary">Yes</a>
                <button type="button" class="btn btn-secondary cancel-modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<!-- delete confirmation modal -->
<div id="delete-confirm-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Confirm Delete</h3>
            <span class="close-modal">&times;</span>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete?</p>
            <div class="form-actions">
                <a href="#" id="confirm-delete-btn" class="btn btn-danger">Yes</a>
                <button type="button" class="btn btn-secondary cancel-modal">Cancel</button>
            </div>
        </div>
    </div>
</div>