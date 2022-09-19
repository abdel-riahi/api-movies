/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
import './styles/app.css';
import './bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import { autocomplete } from './js/autocomplete';
import { getMovie } from './js/modal';
import { fetchmovie } from './js/fetchmovie';

document.addEventListener("DOMContentLoaded", function() {
    autocomplete();
    getMovie();
    fetchmovie();

});