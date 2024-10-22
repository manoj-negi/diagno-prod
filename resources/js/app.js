import './bootstrap';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

window.ClassicEditor = ClassicEditor; // Make it globally available if needed

document.addEventListener('DOMContentLoaded', function () {
    ClassicEditor
        .create(document.querySelector('#ckeditor'))
        .catch(error => {
            console.error(error);
        });
});
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

window.ClassicEditor = ClassicEditor;
