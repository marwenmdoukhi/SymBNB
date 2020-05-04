$('#add-image').click(function () {
    // recuper le numero  des fiture champs que je veux créer
    //+ pour mettre un numero
    const index = +$('#widgets-counter').val();
    console.log(index)
    //je recuper les Protytpe   des entiry

    const tmpl = $('#ad_images').data('prototype').replace(/__name__/g,index);

    // j'inject ce code de la vi
    $('#ad_images').append(tmpl);
    $('#widgets-counter').val(index +1);

    handelDeleteButtons();
});

function handelDeleteButtons()
{
    $('button[data-action="delete"]').click(function () {

        const target = this.dataset.target ;
        $(target).remove();
    });

}

function updateContet() {

    const  count =$('#ad_images div.form-group').length();
    $('#widgets-counter').val(count);

}
updateContet();
handelDeleteButtons();

