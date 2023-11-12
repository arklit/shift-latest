function getCommandBar() {
    const container = document.querySelector('.command-bar');
    const commandBarWrapper = document.querySelector('.command-bar-wrapper');
    const textBlock = commandBarWrapper.querySelector('h1');
    const breadcrumbs = document.querySelector('.breadcrumb');
    const clonedText = textBlock.cloneNode(true);
    const clonedBreadcrumbs = breadcrumbs ? breadcrumbs.cloneNode(true) : null;

    let newContainer;
    let btnContainer;

    setTimeout(() => {
        const oldContainer = document.querySelector('.mobile-commandbar');
        if (oldContainer) {
            oldContainer.remove();
        }
    }, 300);

    const createMobileCommandBar = () => {
        newContainer = document.createElement('div');
        newContainer.classList.add('mobile-commandbar', 'show');
        btnContainer = document.createElement('div');
        btnContainer.classList.add('mobile-commandbar-btn');

        newContainer.append(btnContainer);
        btnContainer.innerHTML = container.innerHTML;
        if (clonedBreadcrumbs) {
            newContainer.prepend(clonedBreadcrumbs);
        }
        newContainer.prepend(clonedText);

        document.body.appendChild(newContainer);

        newContainer.style.opacity = '0';
        newContainer.style.transition = 'opacity 0.5s, transform 0.5s';
        newContainer.style.position = 'fixed';
        newContainer.style.top = '0';
        newContainer.style.transform = 'translateY(-400px)';

        setTimeout(() => {
            newContainer.style.opacity = '1';
            newContainer.style.transform = 'translateY(20px)';
        }, 100);
    };

    const removeMobileCommandBar = () => {
        if (newContainer) {
            newContainer.style.opacity = '0';
            newContainer.style.transform = 'translateY(-100px)';

            setTimeout(() => {
                newContainer.remove();
                newContainer = null;
            }, 500);
        }
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) {
                if (!newContainer) {
                    createMobileCommandBar();
                }
            } else {
                removeMobileCommandBar();
            }
        });
    });

    observer.observe(container);
}

document.addEventListener('turbo:load', () => {
    getCommandBar();
});

getCommandBar();
