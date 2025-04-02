// document.addEventListener('DOMContentLoaded', function() {
//     //initialize modals
//     initModals();
// });

// function initModals() {
//     //get all modal open buttons
//     const openModalButtons = document.querySelectorAll('.open-modal');
    
//     //add click event listeners to open buttons
//     openModalButtons.forEach(button => {
//         button.addEventListener('click', function(e) {
//             e.preventDefault();
//             const modalId = this.getAttribute('data-modal');
//             openModal(modalId);
//         });
//     });
    
//     //get all close buttons
//     const closeModalButtons = document.querySelectorAll('.close-modal');
    
//     //add click event listeners to close buttons
//     closeModalButtons.forEach(button => {
//         button.addEventListener('click', function() {
//             //find the closest modal parent
//             const modal = this.closest('.modal');
//             if (modal) {
//                 closeModal(modal.id);
//             }
//         });
//     });
    
//     //close modal when clicking outside
//     const modals = document.querySelectorAll('.modal');
//     modals.forEach(modal => {
//         modal.addEventListener('click', function(e) {
//             if (e.target === this) {
//                 closeModal(this.id);
//             }
//         });
//     });

//     //initialize edit buttons
//     const editButtons = document.querySelectorAll('.edit-member-btn');
//     editButtons.forEach(button => {
//         button.addEventListener('click', function(e) {
//             e.preventDefault();
//             const memberData = JSON.parse(this.getAttribute('data-member'));
//             populateEditForm(memberData);
//             openModal('edit-member-modal');
//         });
//     });
    
//     //initialize research title add buttons
//     initResearchTitleButtons();
// }

// function initResearchTitleButtons() {
//     //function to add research title field
//     function addResearchTitleField(container) {
//         const newField = document.createElement('div');
//         newField.className = 'research-title-input';
//         newField.innerHTML = `
//             <div class="d-flex mt-2">
//                 <textarea name="research_title[]" class="research-title" rows="2"></textarea>
//                 <button type="button" class="btn btn-sm btn-danger remove-research-title ml-2">Remove</button>
//             </div>
//         `;
//         container.appendChild(newField);
        
//         //add event listener to the remove button
//         newField.querySelector('.remove-research-title').addEventListener('click', function() {
//             container.removeChild(newField);
//         });
//     }
    
//     //add event listeners for the add buttons
//     document.querySelectorAll('.add-research-title').forEach(button => {
//         button.addEventListener('click', function() {
//             const container = this.closest('.form-group').querySelector('[id$=-research-titles-container]');
//             addResearchTitleField(container);
//         });
//     });
    
//     //add event listeners for any existing remove buttons
//     document.querySelectorAll('.remove-research-title').forEach(button => {
//         button.addEventListener('click', function() {
//             const container = this.closest('[id$=-research-titles-container]');
//             container.removeChild(this.closest('.research-title-input'));
//         });
//     });
// }

// function openModal(modalId) {
//     const modal = document.getElementById(modalId);
//     if (modal) {
//         modal.style.display = 'block';
//         document.body.style.overflow = 'hidden'; //prevent scrolling
//     }
// }

// function closeModal(modalId) {
//     const modal = document.getElementById(modalId);
//     if (modal) {
//         modal.style.display = 'none';
//         document.body.style.overflow = 'auto'; //re-enable scrolling
//     }
// }

// function populateEditForm(memberData) {
//     //set member ID
//     document.getElementById('edit_member_id').value = memberData.id;
    
//     //set common fields
//     document.getElementById('edit_name').value = memberData.name;
//     document.getElementById('edit_institute').value = memberData.institute || '';
//     document.getElementById('edit_education').value = memberData.education || '';
    
//     //set existing photo path
//     document.getElementById('existing_photo_path').value = memberData.photo_path || '';
    
//     //show current photo if available
//     const photoContainer = document.getElementById('current_photo_container');
//     const currentPhoto = document.getElementById('current_photo');
//     if (memberData.photo_path) {
//         currentPhoto.src = BASE_URL + '/' + memberData.photo_path;
//         photoContainer.style.display = 'block';
//     } else {
//         photoContainer.style.display = 'none';
//     }
    
//     //set type-specific fields
//     if (memberData.hasOwnProperty('academic_rank')) {
//         //faculty member
//         document.getElementById('edit_academic_rank').value = memberData.academic_rank || '';
        
//         //handle research titles as array
//         const container = document.getElementById('edit-research-titles-container');
//         if (container) {
//             //clear existing research title fields
//             container.innerHTML = '';
            
//             try {
//                 //try to parse research_title as JSON array
//                 let researchTitles = [];
//                 if (memberData.research_title) {
//                     if (typeof memberData.research_title === 'string') {
//                         //attempt to parse as JSON
//                         try {
//                             researchTitles = JSON.parse(memberData.research_title);
//                             //if it's not an array, convert to array
//                             if (!Array.isArray(researchTitles)) {
//                                 researchTitles = [memberData.research_title];
//                             }
//                         } catch (e) {
//                             //if parsing fails, treat as single string
//                             researchTitles = [memberData.research_title];
//                         }
//                     } else {
//                         //if already an object, convert to array
//                         researchTitles = [memberData.research_title];
//                     }
//                 }
                
//                 if (researchTitles.length > 0) {
//                     //add first research title with add button
//                     const firstField = document.createElement('div');
//                     firstField.className = 'research-title-input';
//                     firstField.innerHTML = `
//                         <textarea name="research_title[]" class="research-title" rows="2">${researchTitles[0]}</textarea>
//                         <button type="button" class="btn btn-sm btn-primary add-research-title">+ Add another</button>
//                     `;
//                     container.appendChild(firstField);
                    
//                     //add additional titles
//                     for (let i = 1; i < researchTitles.length; i++) {
//                         const additionalField = document.createElement('div');
//                         additionalField.className = 'research-title-input';
//                         additionalField.innerHTML = `
//                             <div class="d-flex mt-2">
//                                 <textarea name="research_title[]" class="research-title" rows="2">${researchTitles[i]}</textarea>
//                                 <button type="button" class="btn btn-sm btn-danger remove-research-title ml-2">Remove</button>
//                             </div>
//                         `;
//                         container.appendChild(additionalField);
//                     }
//                 } else {
//                     //if no research titles, add empty field
//                     const emptyField = document.createElement('div');
//                     emptyField.className = 'research-title-input';
//                     emptyField.innerHTML = `
//                         <textarea name="research_title[]" class="research-title" rows="2"></textarea>
//                         <button type="button" class="btn btn-sm btn-primary add-research-title">+ Add another</button>
//                     `;
//                     container.appendChild(emptyField);
//                 }
                
//                 //reinitialize research title buttons
//                 initResearchTitleButtons();
                
//             } catch (error) {
//                 console.error("Error processing research titles:", error);
//                 //fallback to single research title field
//                 container.innerHTML = `
//                     <div class="research-title-input">
//                         <textarea name="research_title[]" class="research-title" rows="2">${memberData.research_title || ''}</textarea>
//                         <button type="button" class="btn btn-sm btn-primary add-research-title">+ Add another</button>
//                     </div>
//                 `;
//                 initResearchTitleButtons();
//             }
//         }
        
//         //set other faculty-specific fields if they exist
//         if (document.getElementById('edit_research_link')) {
//             document.getElementById('edit_research_link').value = memberData.research_link || '';
//         }
//         if (document.getElementById('edit_google_scholar_link')) {
//             document.getElementById('edit_google_scholar_link').value = memberData.google_scholar_link || '';
//         }
//     } else if (document.getElementById('edit_position')) {
//         //staff member
//         document.getElementById('edit_position').value = memberData.position || '';
//     }
// }

// document.addEventListener('DOMContentLoaded', function() {
//     //function to add research title field
//     function addResearchTitleField(container) {
//         const newField = document.createElement('div');
//         newField.className = 'research-title-input';
//         newField.innerHTML = `
//             <div class="d-flex mt-2">
//                 <textarea name="research_title[]" class="research-title" rows="2"></textarea>
//                 <button type="button" class="btn btn-sm btn-danger remove-research-title ml-2">Remove</button>
//             </div>
//         `;
//         container.appendChild(newField);
        
//         //add event listener to the remove button
//         newField.querySelector('.remove-research-title').addEventListener('click', function() {
//             container.removeChild(newField);
//         });
//     }
    
//     //add event listeners for the add buttons
//     document.querySelectorAll('.add-research-title').forEach(button => {
//         button.addEventListener('click', function() {
//             const container = this.closest('.form-group').querySelector('[id$=research-titles-container]');
//             addResearchTitleField(container);
//         });
//     });
    
//     //for the edit modal, we'll populate the fields when opening
//     if (typeof editMember === 'function') {
//         const originalEditMember = editMember;
//         editMember = function(id) {
//             originalEditMember(id);
            
//             //the rest of your edit member code is here
//             //add code to populate research titles
//             const researchTitles = memberData.research_title ? JSON.parse(memberData.research_title) : [];
//             const container = document.getElementById('edit-research-titles-container');
            
//             //clear existing fields
//             container.innerHTML = '';
            
//             if (researchTitles.length > 0) {
//                 researchTitles.forEach((title, index) => {
//                     if (index === 0) {
//                         //first field with add button
//                         container.innerHTML = `
//                             <div class="research-title-input">
//                                 <textarea name="research_title[]" class="research-title" rows="2">${title}</textarea>
//                                 <button type="button" class="btn btn-sm btn-primary add-research-title">+ Add another</button>
//                             </div>
//                         `;
//                     } else {
//                         //additional fields with remove button
//                         const newField = document.createElement('div');
//                         newField.className = 'research-title-input';
//                         newField.innerHTML = `
//                             <div class="d-flex mt-2">
//                                 <textarea name="research_title[]" class="research-title" rows="2">${title}</textarea>
//                                 <button type="button" class="btn btn-sm btn-danger remove-research-title ml-2">Remove</button>
//                             </div>
//                         `;
//                         container.appendChild(newField);
//                     }
//                 });
//             } else {
//                 //if no research titles, add an empty field
//                 container.innerHTML = `
//                     <div class="research-title-input">
//                         <textarea name="research_title[]" class="research-title" rows="2"></textarea>
//                         <button type="button" class="btn btn-sm btn-primary add-research-title">+ Add another</button>
//                     </div>
//                 `;
//             }
            
//             //reattach event listeners
//             document.querySelectorAll('.add-research-title').forEach(button => {
//                 button.addEventListener('click', function() {
//                     const container = this.closest('.form-group').querySelector('[id$=research-titles-container]');
//                     addResearchTitleField(container);
//                 });
//             });
            
//             document.querySelectorAll('.remove-research-title').forEach(button => {
//                 button.addEventListener('click', function() {
//                     container.removeChild(this.closest('.research-title-input'));
//                 });
//             });
//         };
//     }
// });

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

    //edit member functionality
    document.querySelectorAll('.edit-member-btn').forEach(btn => {
        btn.addEventListener('click', e => {
            e.preventDefault();
            populateEditForm(JSON.parse(btn.getAttribute('data-member')));
            openModal('edit-member-modal');
        });
    });
    
    initResearchTitleButtons();
}


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
    const deleteButtons = document.querySelectorAll('.delete-member-btn');
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



//filter bbutton
function initResearchTitleButtons() {
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
                <textarea name="research_title[]" class="research-title" rows="2"></textarea>
                <button type="button" class="btn btn-sm btn-danger remove-research-title ml-2">Remove</button>
            </div>
        `;
        container.appendChild(field);
        field.querySelector('.remove-research-title').addEventListener('click', () => container.removeChild(field));
    }
    
    //add button handlers - fixed selector
    document.querySelectorAll('.add-research-title').forEach(btn => {
        btn.addEventListener('click', () => {
            //fix the selector - checking for both patterns
            const container = btn.closest('.form-group')?.querySelector('[id$=-research-titles-container]') || 
            btn.closest('.form-group')?.querySelector('[id$=research-titles-container]');
            
            if (container) {
                addResearchField(container);
            } else {
                console.error("Research titles container not found");
            }
        });
    });
    
    //remove button handlers
    document.querySelectorAll('.remove-research-title').forEach(btn => {
        btn.addEventListener('click', () => {
            const titleInput = btn.closest('.research-title-input');
            if (titleInput && titleInput.parentNode) {
                titleInput.parentNode.removeChild(titleInput);
            }
        });
    });
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

function populateEditForm(memberData) {
    //set basic fields
    const elements = {
        id: document.getElementById('edit_member_id'),
        name: document.getElementById('edit_name'),
        institute: document.getElementById('edit_institute'),
        education: document.getElementById('edit_education'),
        existingPhotoPath: document.getElementById('existing_photo_path'),
        photoContainer: document.getElementById('current_photo_container'),
        currentPhoto: document.getElementById('current_photo')
    };
    
    //safely set values with null checks
    if (elements.id) elements.id.value = memberData.id;
    if (elements.name) elements.name.value = memberData.name;
    if (elements.institute) elements.institute.value = memberData.institute || '';
    if (elements.education) elements.education.value = memberData.education || '';
    
    //handle photo with null checks
    if (elements.existingPhotoPath) elements.existingPhotoPath.value = memberData.photo_path || '';
    
    if (elements.photoContainer && elements.currentPhoto) {
        if (memberData.photo_path) {
            elements.currentPhoto.src = BASE_URL + '/' + memberData.photo_path;
            elements.photoContainer.style.display = 'block';
        } else {
            elements.photoContainer.style.display = 'none';
        }
    }
    
    //handle specific member types
    if (memberData.hasOwnProperty('academic_rank')) {
        //faculty member
        const academicRank = document.getElementById('edit_academic_rank');
        if (academicRank) academicRank.value = memberData.academic_rank || '';
        
        //research titles
        const container = document.getElementById('edit-research-titles-container');
        if (container) {
            container.innerHTML = '';
            
            //parse research titles
            let researchTitles = [];
            if (memberData.research_title) {
                try {
                    if (typeof memberData.research_title === 'string') {
                        researchTitles = JSON.parse(memberData.research_title);
                        if (!Array.isArray(researchTitles)) researchTitles = [memberData.research_title];
                    } else {
                        researchTitles = [memberData.research_title];
                    }
                } catch (e) {
                    researchTitles = [memberData.research_title];
                }
            }
            
            //add research title fields
            if (researchTitles.length > 0) {
                //first field with add button
                container.innerHTML = `
                    <div class="research-title-input">
                        <textarea name="research_title[]" class="research-title" rows="2">${researchTitles[0]}</textarea>
                        <button type="button" class="btn btn-sm btn-primary add-research-title">+ Add another</button>
                    </div>
                `;
                
                //additional fields
                for (let i = 1; i < researchTitles.length; i++) {
                    const field = document.createElement('div');
                    field.className = 'research-title-input';
                    field.innerHTML = `
                        <div class="d-flex mt-2">
                            <textarea name="research_title[]" class="research-title" rows="2">${researchTitles[i]}</textarea>
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
                        <button type="button" class="btn btn-sm btn-primary add-research-title">+ Add another</button>
                    </div>
                `;
            }
            
            //re-initialize buttons
            initResearchTitleButtons();
        }
        
        //set other faculty fields
        const researchLink = document.getElementById('edit_research_link');
        const scholarLink = document.getElementById('edit_google_scholar_link');
        
        if (researchLink) researchLink.value = memberData.research_link || '';
        if (scholarLink) scholarLink.value = memberData.google_scholar_link || '';
    } else {
        //staff member
        const position = document.getElementById('edit_position');
        if (position) position.value = memberData.position || '';
    }
}