import './bootstrap';

const originalFetch = window.fetch;
window.fetch = async function(...args) {
    const start = Date.now();
    const response = await originalFetch(...args);
    const duration = Date.now() - start;

    if (duration > 500) {
        console.warn(`La solicitud a ${args[0]} tardó ${duration}ms`);
    }

    if (!response.ok) {
        const error = await response.json();
        throw new Error(error.message || 'Error en la solicitud');
    }

    return response;
};

Echo.channel('weather-updates')
    .listen('.weather.updated', () => {
        location.reload(); // Recarga la página cuando se recibe el evento
    });
