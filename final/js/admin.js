const postsManagmentContent = document.getElementById('posts-managment-content');
const usersManagmentContent = document.getElementById('users-managment-content');
const postsManagment = document.getElementById('post-management');
const userManagment = document.getElementById('user-management');

postsManagment.addEventListener('click', () => {
    postsManagmentContent.style.display = 'block';
    usersManagmentContent.style.display = 'none';
    userManagment.classList.remove('active');
    postsManagment.classList.add('active');
});

userManagment.addEventListener('click', () => {
    postsManagmentContent.style.display = 'none';
    usersManagmentContent.style.display = 'block';
    userManagment.classList.add('active');
    postsManagment.classList.remove('active');
});
