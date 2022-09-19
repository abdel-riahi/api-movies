export function autocomplete() {
  const input = document.querySelector('#query')
  input.addEventListener('keyup', () => {
      let search_terms = window.movies.map(({ title }) => title)
      let list = '';
      let terms = autocompleteMatch(input.value, search_terms);
      for (let i=0; i<terms.length; i++) {
          list += '<li>' + terms[i] + '</li>';
      }
      document.querySelector("#result").innerHTML = '<ul>' + list + '</ul>';
  })
}
function autocompleteMatch(input, search_terms) {
  if (input == '') {
    return [];
  }
  let reg = new RegExp(input)
  return search_terms.filter(function(term) {
      if (term.match(reg)) {
        return term;
      }
  });
}
