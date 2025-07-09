// document.addEventListener('DOMContentLoaded', function() {
//     //get DOM elements
//     const searchInput = document.querySelector('.search-input');
//     const instituteSelect = document.querySelector('.institute-select');
//     const searchButton = document.querySelector('.search-button');
//     const letterLinks = document.querySelectorAll('.letter-link');
//     const tableRows = document.querySelectorAll('.table tbody tr');
    
//     //current filters
//     let currentFilters = {
//         name: '',
//         institute: '',
//         letter: ''
//     };
    
//     //search by name when search button is clicked
//     searchButton.addEventListener('click', function() {
//         currentFilters.name = searchInput.value.trim().toLowerCase();
//         applyFilters();
//     });
    
//     //search when Enter key is pressed in the search input
//     searchInput.addEventListener('keypress', function(e) {
//         if (e.key === 'Enter') {
//             currentFilters.name = searchInput.value.trim().toLowerCase();
//             applyFilters();
//         }
//     });
    
//     //filter by institute when select is changed
//     instituteSelect.addEventListener('change', function() {
//         currentFilters.institute = this.value.toLowerCase();
//         applyFilters();
//     });
    
//     //filter by alphabet letter
//     letterLinks.forEach(link => {
//         link.addEventListener('click', function(e) {
//             e.preventDefault();
            
//             //toggle active class on letter links
//             letterLinks.forEach(l => l.classList.remove('active'));
//             this.classList.add('active');
            
//             currentFilters.letter = this.textContent.trim().toLowerCase();
//             applyFilters();
//         });
//     });
    
//     //apply all current filters
//     function applyFilters() {
//         tableRows.forEach(row => {
//             const name = row.querySelector('.member-name').textContent.trim().toLowerCase();
//             const institute = row.cells[3].textContent.trim().toLowerCase();
            
//             //check if row passes all active filters
//             const passesNameFilter = currentFilters.name === '' || name.includes(currentFilters.name);
//             const passesInstituteFilter = currentFilters.institute === '' || institute.includes(currentFilters.institute);
//             const passesLetterFilter = currentFilters.letter === '' || name.charAt(0).toLowerCase() === currentFilters.letter;
            
//             //show/hide row based on filter results
//             if (passesNameFilter && passesInstituteFilter && passesLetterFilter) {
//                 row.style.display = '';
//             } else {
//                 row.style.display = 'none';
//             }
//         });
        
//         //show message if no results
//         showNoResultsMessage();
//     }
    
//     //display a message when no results match filters
//     function showNoResultsMessage() {
//         const visibleRows = document.querySelectorAll('.table tbody tr:not([style*="display: none"])').length;
//         let noResultsMsg = document.querySelector('.no-results-message');
        
//         if (visibleRows === 0) {
//             if (!noResultsMsg) {
//                 noResultsMsg = document.createElement('p');
//                 noResultsMsg.className = 'no-results-message';
//                 noResultsMsg.textContent = 'No members found matching your filters.';
//                 document.querySelector('.table').insertAdjacentElement('afterend', noResultsMsg);
//             }
//         } else if (noResultsMsg) {
//             noResultsMsg.remove();
//         }
//     }
    
//     //add a clear filters button
//     function addClearFiltersButton() {
//         const searchContainer = document.querySelector('.search-form');
        
//         //create clear button if it doesn't exist
//         if (!document.querySelector('.clear-filters')) {
//             const clearButton = document.createElement('button');
//             clearButton.className = 'clear-filters';
//             clearButton.textContent = 'Clear Filters';
//             clearButton.addEventListener('click', function() {
//                 //reset all filters
//                 searchInput.value = '';
//                 instituteSelect.selectedIndex = 0;
//                 letterLinks.forEach(l => l.classList.remove('active'));
                
//                 currentFilters = {
//                     name: '',
//                     institute: '',
//                     letter: ''
//                 };
                
//                 applyFilters();
//             });
            
//             searchContainer.appendChild(clearButton);
//         }
//     }
    
//     //initialize
//     addClearFiltersButton();
// });
document.addEventListener('DOMContentLoaded', function() {
   
    const searchInput = document.querySelector('.search-input');
    const instituteSelect = document.querySelector('.institute-select');
    const searchButton = document.querySelector('.search-button');
    const letterLinks = document.querySelectorAll('.letter-link');
    const tableRows = document.querySelectorAll('.table tbody tr');
    const recordsFoundElement = document.querySelector('.actions span');
    

    //detect type from URL (faculty, staff, office)
    const urlParams = new URLSearchParams(window.location.search);
    const type = urlParams.get('type') || 'faculty';


    //current filters
    let currentFilters = {
        name: '',
        institute: '',
        letter: ''
    };
    
    //initialize records count
    updateRecordsCount();
    
    //search by name when search button is clicked
    searchButton.addEventListener('click', function() {
        currentFilters.name = searchInput.value.trim().toLowerCase();
        applyFilters();
    });
    
    //search when Enter key is pressed in the search input
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            currentFilters.name = searchInput.value.trim().toLowerCase();
            applyFilters();
        }
    });
    
    //filter by institute when select is changed
    instituteSelect.addEventListener('change', function() {
        currentFilters.institute = this.value.toLowerCase();
        applyFilters();
    });
    
    //filter by alphabet letter
    letterLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            //toggle active class on letter links
            letterLinks.forEach(l => l.classList.remove('active'));
            this.classList.add('active');
            
            currentFilters.letter = this.textContent.trim().toLowerCase();
            applyFilters();
        });
    });
    
    //apply all current filters
    function applyFilters() {
        let visibleRowsCount = 0;
        
        tableRows.forEach(row => {
            let name, instituteOrOffice;
            if (type === 'office') {
                name = row.querySelector('.list-name') 
                    ? row.querySelector('.list-name').textContent.trim().toLowerCase()
                    : row.cells[0].textContent.trim().toLowerCase(); // fallback
                //office_name is in the first column
                instituteOrOffice = row.cells[0].textContent.trim().toLowerCase();
            } else {
                name = row.querySelector('.list-name')
                    ? row.querySelector('.list-name').textContent.trim().toLowerCase()
                    : row.cells[2].textContent.trim().toLowerCase(); // fallback
                // institute is in the 4th column (index 3)
                instituteOrOffice = row.cells[3] ? row.cells[3].textContent.trim().toLowerCase() : '';
            }
            
            //check if row passes all active filters
            const passesNameFilter = currentFilters.name === '' || name.includes(currentFilters.name);
            const passesInstituteFilter = currentFilters.institute === '' || instituteOrOffice.includes(currentFilters.institute);
            const passesLetterFilter = currentFilters.letter === '' || name.charAt(0).toLowerCase() === currentFilters.letter;
            
            //show/hide row based on filter results
            if (passesNameFilter && passesInstituteFilter && passesLetterFilter) {
                row.style.display = '';
                visibleRowsCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        //update the records count display
        updateRecordsCount(visibleRowsCount);
        
        //show message if no results
        showNoResultsMessage(visibleRowsCount);
    }
    
    //update the records count display
    function updateRecordsCount(count) {
        if (typeof count === 'undefined') {
            //if no count provided, count all rows
            count = tableRows.length;
        }
        
        //create filter description
        let filterDescription = '';
        if (currentFilters.name) {
            filterDescription += ` matching "${currentFilters.name}"`;
        }
        if (currentFilters.institute) {
            filterDescription += ` in "${currentFilters.institute}"`;
        }
        if (currentFilters.letter) {
            filterDescription += ` starting with "${currentFilters.letter.toUpperCase()}"`;
        }
        
        recordsFoundElement.textContent = `Records found: ${count}${filterDescription}`;
    }
    
    //display a message when no results match filters
    function showNoResultsMessage(visibleRowsCount) {
        let noResultsMsg = document.querySelector('.no-results-message');
        
        if (visibleRowsCount === 0) {
            if (!noResultsMsg) {
                noResultsMsg = document.createElement('p');
                noResultsMsg.className = 'no-results-message';
                noResultsMsg.textContent = 'No lists found matching your filters.';
                document.querySelector('.table').insertAdjacentElement('afterend', noResultsMsg);
            }
        } else if (noResultsMsg) {
            noResultsMsg.remove();
        }
    }
    
    //add a clear filters button
    function addClearFiltersButton() {
        const searchContainer = document.querySelector('.search-form');
        
        //create clear button if it doesn't exist
        if (!document.querySelector('.clear-filters')) {
            const clearButton = document.createElement('button');
            clearButton.className = 'clear-filters';
            clearButton.textContent = 'Clear Filters';
            clearButton.addEventListener('click', function() {
                //reset all filters
                searchInput.value = '';
                instituteSelect.selectedIndex = 0;
                letterLinks.forEach(l => l.classList.remove('active'));
                
                currentFilters = {
                    name: '',
                    institute: '',
                    letter: ''
                };
                
                applyFilters();
            });
            
            searchContainer.appendChild(clearButton);
        }
    }
    
    //initialize
    addClearFiltersButton();
});