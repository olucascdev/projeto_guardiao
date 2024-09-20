let static = window.pageYoffset
document.addEventListener('scroll', ()=>{
    scrollTo(0, static);
});


(function(win,doc){
    'use strict';

    doc.querySelector('#estado').addEventListener('change',async(e)=>{
        let reqs = await fetch ('Controller/CidadeController.php',{
            method:'post',
            headers:{
                'Content-Type':'application/x-www-form-urlencoded'
            },
            body: 'estado=${e.target.value}'
        });
        let ress = await reqs.json();
        let selCidades = doc.querySelector('#cidade');
        selCidades.options.length = 1;
        ress.map((elem,ind,obj)=>{
            let opt = doc.createElement('option');
            opt.value = elem.id;
            opt.innerHTML = elem.nome;
            selCidades.appendChild(opt);
        });
        selCidades.removeAttribute('disable');
    });

})(window,document);