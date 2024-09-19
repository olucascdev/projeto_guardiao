let static = window.pageYoffset
document.addEventListener('scroll', ()=>{
    scrollTo(0, static);
});