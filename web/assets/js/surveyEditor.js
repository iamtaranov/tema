
var editorContainer = document.getElementById("survey-editor_sortable");

autosize(document.querySelectorAll('textarea'));

Sortable.create(document.getElementById("survey-editor_sortable"), {
    animation: 150, // ms, animation speed moving items when sorting, `0` — without animation
    handle: ".item", // Restricts sort start click/touch to the specified element
    draggable: ".item", // Specifies which items inside the element should be sortable
    onUpdate: function (event){
        var item = event.item; // the current dragged HTMLElement
    }
});

setClickListeners(document.querySelectorAll(".btn_delete-item"), onClickDeleteItem = function(item)
{
    if (item.closest("ul, ol").childElementCount <= 1)
    {
        alert("Вы не можете удалить этот элемент!");
        return false;
    }

    item.closest("li").remove();
});

setClickListeners(document.querySelectorAll(".btn_tpl[data-tpl]"), onClickCreateItem = function(btn)
{
    var query = btn.dataset.tpl;

    if ((key = btn.dataset.key) != undefined)
    {
        query += "?identifier=" + key;
    }

    fetch("/api/v2/template/editor/" + query)
        .then(function(response)
        {
            return response.json();
        })
        .then(function(response)
        {
            var item = document.createElement("li");
            item.className = "item";
            item.innerHTML = response.template;
            setClickListeners(item.querySelectorAll(".btn_delete-item"), onClickDeleteItem);
            setClickListeners(item.querySelectorAll(".btn_create-item"), onClickCreateItem);

            switch (btn.dataset.tpl)
            {
                case "selectable_item":

                    btn.previousElementSibling.appendChild(item);

                    break;

                default: editorContainer.appendChild(item);
            }
        });

    return false;
});