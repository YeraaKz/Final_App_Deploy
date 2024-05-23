function addCollectionAttribute() {
    const collectionHolder = document.querySelector('#custom-attributes-wrapper');

    const item = document.createElement('div');
    item.classname = 'item';

    item.innerHTML = collectionHolder
        .dataset
        .prototype
        .replace(
            /__name__/g,
            collectionHolder.dataset.index
        );

    item.setAttribute('class', 'item mb-2')

    collectionHolder.appendChild(item);

    collectionHolder.dataset.index++;

    addRemoveAttributeButton(item);
}

function addRemoveAttributeButton(item){
    const removeFormButton = document.createElement('a');
    removeFormButton.href = '#';
    removeFormButton.innerText = 'Delete';

    item.append(removeFormButton);

    removeFormButton.addEventListener('click', (e) => {
        e.preventDefault();
        // remove the li for the tag form
        item.remove();
    });
}

document.addEventListener('DOMContentLoaded', () => {
    document
        .querySelector('#add-custom-attribute')
        .addEventListener('click', (e) => {
            e.preventDefault();

            addCollectionAttribute()
        });

    document
        .querySelectorAll('#custom-attributes-wrapper div.item')
        .forEach(() => {
            addRemoveAttributeButton(row);
        });
});