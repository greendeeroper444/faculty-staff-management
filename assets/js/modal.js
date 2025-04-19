document.addEventListener('DOMContentLoaded', function() {
    initModals();
    initConfirmationModals();
});

//modal default
function initModals() {
    //modal open/close functionality
    document.querySelectorAll('.open-modal').forEach(btn => {
        btn.addEventListener('click', e => {
            e.preventDefault();
            openModal(btn.getAttribute('data-modal'));
        });
    });
    
    //close-modal
    document.querySelectorAll('.close-modal, .cancel-modal').forEach(btn => {
        btn.addEventListener('click', () => {
            const modal = btn.closest('.modal');
            if (modal) closeModal(modal.id);
        });
    });
    
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', e => {
            if (e.target === modal) closeModal(modal.id);
        });
    });

    //edit list functionality
    document.querySelectorAll('.edit-faculty-staff-list-modal').forEach(btn => {
        btn.addEventListener('click', e => {
            e.preventDefault();
            populateEditForm(JSON.parse(btn.getAttribute('data-list')));
            openModal('edit-faculty-staff-list-modal');
        });
    });
    
    initResearchTitleButtons();
}

function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

function populateEditForm(listData) {
    
    //set basic fields
    const elements = {
        id: document.getElementById('edit_list_id'),
        name: document.getElementById('edit_name'),
        institute: document.getElementById('edit_institute'),
        existingPhotoPath: document.getElementById('existing_photo_path'),
        photoContainer: document.getElementById('current_photo_container'),
        currentPhoto: document.getElementById('current_photo'),
        academicRank: document.getElementById('edit_academic_rank'),
        designation: document.getElementById('edit_designation'),
        email: document.getElementById('edit_email'),

        //office directory
        office_name: document.getElementById('edit_office_name'),
        about: document.getElementById('edit_about'),
        head: document.getElementById('edit_head'),
        contact_number: document.getElementById('edit_contact_number'),
    };
    
    //safely set values with null checks
    if (elements.id) elements.id.value = listData.id;
    if (elements.name) elements.name.value = listData.name;
    if (elements.institute) elements.institute.value = listData.institute || '';
    if (elements.academicRank) elements.academicRank.value = listData.academic_rank || '';
    if (elements.designation) elements.designation.value = listData.designation || '';
    if (elements.email) elements.email.value = listData.email || '';

    //office directory
    if (elements.office_name) elements.office_name.value = listData.office_name;
    if (elements.about) elements.about.value = listData.about || '';
    if (elements.head) elements.head.value = listData.head || '';
    if (elements.contact_number) elements.contact_number.value = listData.contact_number || '';
    //handle photo with null checks
    if (elements.existingPhotoPath) elements.existingPhotoPath.value = listData.photo_path || '';
    
    if (elements.photoContainer && elements.currentPhoto) {
        if (listData.photo_path) {
            elements.currentPhoto.src = BASE_URL + '/' + listData.photo_path;
            elements.photoContainer.style.display = 'block';
        } else {
            elements.photoContainer.style.display = 'none';
        }
    }
    
    //handle specific list types
    if (listData.hasOwnProperty('academic_rank')) {
        //faculty list
        const academicRank = document.getElementById('edit_academic_rank');
        if (academicRank) academicRank.value = listData.academic_rank || '';
        
        //handle education - parse education
        let educationList = [];
        if (listData.education) {
            try {
                if (typeof listData.education === 'string') {
                    if (listData.education.startsWith('[') && listData.education.endsWith(']')) {
                        educationList = JSON.parse(listData.education);
                    } else {
                        educationList = [listData.education];
                    }
                } else if (Array.isArray(listData.education)) {
                    educationList = listData.education;
                } else {
                    educationList = [listData.education];
                }
            } catch (e) {
                educationList = [listData.education || ''];
            }
        }
        
        //populate education container
        const educationContainer = document.getElementById('edit-education-container');
        if (educationContainer) {
            educationContainer.innerHTML = '';
            
            if (educationList.length > 0) {
                //first field with add button
                educationContainer.innerHTML = `
                    <div class="research-title-input">
                        <textarea name="education[]" class="research-title" rows="2">${educationList[0]}</textarea>
                        <button type="button" class="btn btn-sm btn-primary add-more mt-2">+ Add more education</button>
                    </div>
                `;
                
                //additional fields
                for (let i = 1; i < educationList.length; i++) {
                    const field = document.createElement('div');
                    field.className = 'research-title-input';
                    field.innerHTML = `
                        <div class="d-flex mt-2">
                            <div class="flex-grow-1">
                                <textarea name="education[]" class="research-title" rows="2">${educationList[i]}</textarea>
                            </div>
                            <button type="button" class="btn btn-sm btn-danger remove-research-title ml-2">Remove</button>
                        </div>
                    `;
                    educationContainer.appendChild(field);
                }
            } else {
                //empty field
                educationContainer.innerHTML = `
                    <div class="research-title-input">
                        <textarea name="education[]" class="research-title" rows="2"></textarea>
                        <button type="button" class="btn btn-sm btn-primary add-more mt-2">+ Add more research</button>
                    </div>
                `;
            }
        }
        
        //research titles and links
        const container = document.getElementById('edit-research-titles-container');
        if (container) {
            container.innerHTML = '';
            
            //parse research titles
            let researchTitles = [];
            if (listData.research_title) {
                try {
                    if (typeof listData.research_title === 'string') {
                        researchTitles = JSON.parse(listData.research_title);
                        if (!Array.isArray(researchTitles)) researchTitles = [listData.research_title];
                    } else {
                        researchTitles = [listData.research_title];
                    }
                } catch (e) {
                    researchTitles = [listData.research_title];
                }
            }
            
            //parse research links
            let researchLinks = [];
            if (listData.research_link) {
                try {
                    if (typeof listData.research_link === 'string') {
                        if (listData.research_link.startsWith('[') && listData.research_link.endsWith(']')) {
                            researchLinks = JSON.parse(listData.research_link);
                        } else {
                            researchLinks = [listData.research_link];
                        }
                    } else if (Array.isArray(listData.research_link)) {
                        researchLinks = listData.research_link;
                    } else {
                        researchLinks = [listData.research_link];
                    }
                } catch (e) {
                    researchLinks = [listData.research_link || ''];
                }
            }
            
            //make sure both arrays are the same length
            while (researchLinks.length < researchTitles.length) {
                researchLinks.push('');
            }
            
            //add research title fields
            if (researchTitles.length > 0) {
                //first field with add button
                container.innerHTML = `
                    <div class="research-title-input">
                        <textarea name="research_title[]" class="research-title" rows="2">${researchTitles[0]}</textarea>
                        <input type="url" name="research_link[]" class="research-link mt-2" placeholder="https://research-profile.example.com" value="${researchLinks[0] || ''}">
                        <button type="button" class="btn btn-sm btn-primary add-more mt-2">+ Add more research</button>
                    </div>
                `;
                
                //additional fields
                for (let i = 1; i < researchTitles.length; i++) {
                    const field = document.createElement('div');
                    field.className = 'research-title-input';
                    field.innerHTML = `
                        <div class="d-flex mt-2">
                            <div class="flex-grow-1">
                                <textarea name="research_title[]" class="research-title" rows="2">${researchTitles[i]}</textarea>
                                <input type="url" name="research_link[]" class="research-link mt-2" placeholder="https://research-profile.example.com" value="${researchLinks[i] || ''}">
                            </div>
                            <button type="button" class="btn btn-sm btn-danger remove-research-title ml-2">Remove</button>
                        </div>
                    `;
                    container.appendChild(field);
                }
            } else {
                //empty field
                container.innerHTML = `
                    <div class="research-title-input">
                        <textarea name="research_title[]" class="research-title" rows="2"></textarea>
                        <input type="url" name="research_link[]" class="research-link mt-2" placeholder="https://research-profile.example.com">
                        <button type="button" class="btn btn-sm btn-primary add-more mt-2">+ Add more research</button>
                    </div>
                `;
            }
        }
        
        //set google scholar link
        const scholarLink = document.getElementById('edit_google_scholar_link');
        if (scholarLink) scholarLink.value = listData.google_scholar_link || '';
    }
    
    //re-initialize all buttons after populating forms
    initResearchTitleButtons();
    initEducationButtons();
}


function initResearchTitleButtons() {
    //remove any existing event listeners to prevent double-triggering
    document.querySelectorAll('.add-more').forEach(btn => {
        //clone the button to remove all event listeners
        const newBtn = btn.cloneNode(true);
        btn.parentNode.replaceChild(newBtn, btn);
    });
    
    //add research title field - with null check
    function addResearchField(container) {
        if (!container) {
            console.error("Container element not found");
            return; //exit if container is null
        }
        
        const field = document.createElement('div');
        field.className = 'research-title-input';
        field.innerHTML = `
            <div class="d-flex mt-2">
                <div class="flex-grow-1">
                    <label>Research Title</label>
                    <textarea name="research_title[]" class="research-title" rows="2" placeholder="Enter research title"></textarea>
                    <label class="mt-2">Research Link</label>
                    <input type="url" name="research_link[]" class="research-link mt-2" placeholder="https://research-profile.example.com">
                </div>
                <button type="button" class="btn btn-sm btn-danger remove-research-title ml-2">Remove</button>
            </div>
        `;
        container.appendChild(field);
        field.querySelector('.remove-research-title').addEventListener('click', () => container.removeChild(field));
    }
    
    //now attach new event listeners
    document.querySelectorAll('.add-more').forEach(btn => {
        btn.addEventListener('click', function(e) {
            //prevent event from firing multiple times
            e.stopPropagation();
            
            //fix the selector - checking for both patterns
            const container = btn.closest('.form-group')?.querySelector('[id$=-research-titles-container]') || 
                btn.closest('.form-group')?.querySelector('[id$=research-titles-container]');
            
            if (container) {
                addResearchField(container);
            } else {
                //check if this is an education button
                const educationContainer = btn.closest('.form-group')?.querySelector('[id$=-education-container]') ||
                    btn.closest('.form-group')?.querySelector('[id$=education-container]');
                
                if (educationContainer) {
                    //let the education button handler handle this
                    return;
                }
                
                console.error("Container not found");
            }
        });
    });
    
    //remove button handlers - also reset these
    document.querySelectorAll('.remove-research-title').forEach(btn => {
        //clone to remove existing listeners
        const newBtn = btn.cloneNode(true);
        btn.parentNode.replaceChild(newBtn, btn);
        
        //add new listener
        newBtn.addEventListener('click', () => {
            const titleInput = newBtn.closest('.research-title-input');
            if (titleInput && titleInput.parentNode) {
                titleInput.parentNode.removeChild(titleInput);
            }
        });
    });
}

function initEducationButtons() {
    //remove any existing education-specific event listeners
    //(Note: we've already reset .add-more buttons in the research function)
    document.querySelectorAll('.remove-education').forEach(btn => {
        //clone the button to remove all event listeners
        const newBtn = btn.cloneNode(true);
        btn.parentNode.replaceChild(newBtn, btn);
    });
    
    //add education field
    function addEducationField(container) {
        if (!container) {
            console.error("Education container element not found");
            return;
        }
        
        const field = document.createElement('div');
        field.className = 'research-title-input';
        field.innerHTML = `
            <div class="d-flex mt-2">
                <div class="flex-grow-1">
                    <label>Education</label>
                    <textarea name="education[]" class="research-title" rows="2" placeholder="Education background and qualifications"></textarea>
                </div>
                <button type="button" class="btn btn-sm btn-danger remove-education ml-2">Remove</button>
            </div>
        `;
        container.appendChild(field);
        
        //add event listener to the new remove button
        field.querySelector('.remove-education').addEventListener('click', function() {
            if (field && field.parentNode) {
                field.parentNode.removeChild(field);
            }
        });
    }
    
    //add education button handlers
    document.querySelectorAll('.form-group').forEach(group => {
        const educationContainer = group.querySelector('[id$=-education-container]') || 
        group.querySelector('[id$=education-container]');
        
        if (educationContainer) {
            const addButton = group.querySelector('.add-more');
            if (addButton) {
                //we already replaced this button in initResearchTitleButtons
                //just make sure it has a specific education handler
                addButton.addEventListener('click', function(e) {
                    //only handle if this is specifically an education button
                    if (educationContainer) {
                        addEducationField(educationContainer);
                    }
                });
            }
        }
    });
    
    //re-attach event listeners for existing remove education buttons
    document.querySelectorAll('.remove-education').forEach(btn => {
        btn.addEventListener('click', function() {
            const educationInput = this.closest('.research-title-input');
            if (educationInput && educationInput.parentNode) {
                educationInput.parentNode.removeChild(educationInput);
            }
        });
    });
}


function initAllFormButtons() {
    //set a flag to track if initialization has been run
    if (window._buttonsInitialized) {
        //first, reset by cloning all buttons to remove existing listeners
        document.querySelectorAll('.add-more, .remove-research-title, .remove-education').forEach(btn => {
            const newBtn = btn.cloneNode(true);
            if (btn.parentNode) {
                btn.parentNode.replaceChild(newBtn, btn);
            }
        });
    }
    
    //initialize buttons
    initResearchTitleButtons();
    initEducationButtons();
    
    //set the flag
    window._buttonsInitialized = true;
}




//
function initPhotoPreview() {
    //for the add form
    const addPhotoInput = document.getElementById('photo');
    const addPhotoPreview = document.getElementById('picked_photo');
    const addPhotoContainer = document.getElementById('picked_photo_container');
    
    if (addPhotoInput && addPhotoPreview && addPhotoContainer) {
        addPhotoInput.addEventListener('change', function(event) {
            if (event.target.files && event.target.files[0]) {
                const file = event.target.files[0];
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    addPhotoPreview.src = e.target.result;
                    addPhotoContainer.style.display = 'block';
                }
                
                reader.readAsDataURL(file);
            }
        });
    }
}


//initialize both types of buttons when the page loads
document.addEventListener('DOMContentLoaded', function() {
    initResearchTitleButtons();
    initEducationButtons();

    initPhotoPreview();
});


//confimation modal
function initConfirmationModals() {
    //handle logout confirmation
    const logoutLink = document.querySelector('.logout-link');
    if (logoutLink) {
        logoutLink.addEventListener('click', function(e) {
            e.preventDefault();
            openModal('logout-confirm-modal');
        });
    }
    
    //handle delete confirmation
    const deleteButtons = document.querySelectorAll('.delete-confirm-modal');
    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const deleteUrl = this.getAttribute('href');
            const confirmDeleteBtn = document.getElementById('confirm-delete-btn');
            if (confirmDeleteBtn) {
                confirmDeleteBtn.setAttribute('href', deleteUrl);
                openModal('delete-confirm-modal');
            }
        });
    });
}



function initLoadingButtons() {
    document.querySelectorAll('.loading-btn').forEach(button => {
        button.addEventListener('click', function (e) {
            //handle anchor tags
            if (button.tagName === 'A') {
                e.preventDefault();

                //add loading spinner and disable the button
                if (!button.classList.contains('loading')) {
                    button.classList.add('loading');
                    const originalText = button.innerHTML;
                    button.setAttribute('data-original-text', originalText);
                    button.innerHTML = `<span class="spinner"><i class="fas fa-circle-notch fa-spin"></i></span> ${button.getAttribute('data-loading-text') || 'Loading...'}`;
                    button.disabled = true;

                    //redirect after a short delay
                    setTimeout(() => {
                        window.location.href = button.href;
                    }, 1000); //adjust delay as needed
                }
            }

            //handle form submission buttons
            if (button.type === 'submit') {
                e.preventDefault(); // Prevent default form submission

                //add loading spinner and disable the button
                if (!button.classList.contains('loading')) {
                    button.classList.add('loading');
                    const originalText = button.innerHTML;
                    button.setAttribute('data-original-text', originalText);
                    button.innerHTML = `<span class="spinner"><i class="fas fa-circle-notch fa-spin"></i></span> ${button.getAttribute('data-loading-text') || 'Loading...'}`;
                    button.disabled = true;

                    //simulate form submission
                    const form = button.closest('form');
                    if (form) {
                        setTimeout(() => {
                            form.submit(); //submit the form after a short delay
                        }, 1000); //adjust delay as needed
                    }
                }
            }
        });
    });
}

//initialize loading buttons on DOMContentLoaded
document.addEventListener('DOMContentLoaded', function () {
    initLoadingButtons();
});