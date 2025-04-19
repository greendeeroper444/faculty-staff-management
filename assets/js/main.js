document.addEventListener('DOMContentLoaded', function() {
    //toggle mobile menu if exists
    const menuToggle = document.querySelector('.menu-toggle');
    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            const nav = document.querySelector('nav');
            nav.classList.toggle('active');
        });
    }
    
    //flash messages fade out
    const flashMessages = document.querySelectorAll('.message');
    if (flashMessages.length > 0) {
        setTimeout(function() {
            flashMessages.forEach(function(message) {
                message.style.opacity = '0';
                setTimeout(function() {
                    message.style.display = 'none';
                }, 500);
            });
        }, 5000);
    }
});


//old sidebar
// //make an active li a in my sidebar
// document.addEventListener('DOMContentLoaded', function() {
//     //get all li elements in the admin sidebar
//     const menuItems = document.querySelectorAll('.admin-sidebar ul li');
    
//     //add click event listener to each menu item
//     menuItems.forEach(function(item) {
//         item.addEventListener('click', function() {
//             //remove active class from all items
//             menuItems.forEach(function(item) {
//             item.classList.remove('active');
//             });
            
//             //add active class to clicked item
//             this.classList.add('active');
//         });
//     });
    
//     //set active class based on current URL (optional)
//     const currentUrl = window.location.href;
//     menuItems.forEach(function(item) {
//         const link = item.querySelector('a');
//         if (link && currentUrl.includes(link.getAttribute('href'))) {
//             item.classList.add('active');
//         }
//     });
// });

//new active sidebar
//make an active li a in my sidebar
document.addEventListener('DOMContentLoaded', function() {
    //get all li elements in the admin sidebar
    const menuItems = document.querySelectorAll('.admin-sidebar ul li');
    
    //add click event listener to each menu item
    menuItems.forEach(function(item) {
        item.addEventListener('click', function() {
            //remove active class from all items
            menuItems.forEach(function(item) {
                item.classList.remove('active');
            });
            
            //add active class to clicked item
            this.classList.add('active');
        });
    });
    
    //set active class based on current URL and type parameter
    const currentUrl = window.location.href;
    const urlParams = new URLSearchParams(window.location.search);
    const typeParam = urlParams.get('type');
    
    //check if we're on a faculty-staff-list-details page
    if (currentUrl.includes('faculty-staff-list-details.php') && typeParam) {
        //find the corresponding sidebar link based on type
        menuItems.forEach(function(item) {
            const link = item.querySelector('a');
            if (link && link.href.includes(`type=${typeParam}`)) {
                item.classList.add('active');
            }
        });
    } else {
        //for other pages, use the original check
        menuItems.forEach(function(item) {
            const link = item.querySelector('a');
            if (link && currentUrl.includes(link.getAttribute('href'))) {
                item.classList.add('active');
            }
        });
    }
});



//alphabetical list
document.addEventListener('DOMContentLoaded', function() {
    //get all li elements in the admin sidebar
    const menuItems = document.querySelectorAll('.alphabet-filter ul li');
    
    //add click event listener to each menu item
    menuItems.forEach(function(item) {
        item.addEventListener('click', function() {
            //remove active class from all items
            menuItems.forEach(function(item) {
                item.classList.remove('active');
            });
            
            //add active class to clicked item
            this.classList.add('active');
        });
    });
});