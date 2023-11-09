import './../bootstrap'
import './accordion';
import CropperRocont from "./controllers/cropper.js";
import TinyMceController from "./controllers/tinymce";

application.register("cropperrocont", CropperRocont);
application.register("tinymce", TinyMceController);
