import './bootstrap';

let visitDuration = 0;
setInterval(() => visitDuration++, 1000);
document.querySelector('form').addEventListener('submit', () => {
    let input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'visit_duration';
    input.value = visitDuration > 30 ? 1 : 0;
    document.querySelector('form').appendChild(input);
});