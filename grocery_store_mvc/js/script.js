let navbar = document.querySelector('.header .flex .navbar');
let profile = document.querySelector('.header .flex .profile');

document.querySelector('#menu-btn').onclick = () => {
   navbar.classList.toggle('active');
   profile.classList.remove('active');
}

document.querySelector('#user-btn').onclick = () => {
   profile.classList.toggle('active');
   navbar.classList.remove('active');
}

document.querySelector('#search-btn').onclick = () => {
   // Chuyển hướng đến trang search riêng thay vì toggle form
   window.location.href = 'search_page';
}

window.onscroll = () => {
   profile.classList.remove('active');
   navbar.classList.remove('active');
}