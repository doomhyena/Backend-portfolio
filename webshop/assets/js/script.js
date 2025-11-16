(function(){
    const nav = document.querySelector('nav');
    const toggle = document.querySelector('.menu-toggle');
    if(toggle && nav){
        toggle.addEventListener('click', ()=> nav.classList.toggle('open'));
    }
    window.showToast = function(msg, timeout=2200){
        let t = document.querySelector('.toast');
        if(!t){
            t = document.createElement('div');
            t.className = 'toast';
            document.body.appendChild(t);
        }
        t.textContent = msg;
        t.classList.add('show');
        setTimeout(()=> t.classList.remove('show'), timeout);
    };
})();
