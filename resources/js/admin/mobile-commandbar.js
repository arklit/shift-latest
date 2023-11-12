function getCommandBar() {
    const container = document.querySelector('.command-bar');
    const commandBarWrapper = document.querySelector('.command-bar-wrapper');
    const textBlock = commandBarWrapper.querySelector('h1');
    const breadcrumbs = document.querySelector('.breadcrumb');
    let clonedText = textBlock.cloneNode(true);
    let newContainer;
    let btnContainer;

    if (breadcrumbs) {
        var clonedBreadcrumbs = breadcrumbs.cloneNode(true);
    }

    setTimeout(() => {
        const oldContainer = document.querySelector('.mobile-commandbar');
        if (oldContainer) {
            oldContainer.remove();
        }
    }, 300)

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) {
                // Создаем новый контейнер только при первом скрытии главного контейнера
                if (!newContainer) {
                    newContainer = document.createElement('div');
                    newContainer.classList.add('mobile-commandbar');
                    newContainer.classList.add('show');
                    btnContainer = document.createElement('div');
                    btnContainer.classList.add('mobile-commandbar-btn');

                    // Копируем контент скрывающегося контейнера
                    newContainer.append(btnContainer);
                    btnContainer.innerHTML = container.innerHTML;
                    if (clonedBreadcrumbs) {
                        newContainer.prepend(clonedBreadcrumbs);
                    }
                    newContainer.prepend(clonedText);

                    // Добавляем новый контейнер в DOM
                    document.body.appendChild(newContainer);

                    // Анимация появления
                    newContainer.style.opacity = '0';
                    newContainer.style.transition = 'opacity 0.5s, transform 0.5s';
                    newContainer.style.position = 'fixed';
                    newContainer.style.top = '0';
                    newContainer.style.transform = 'translateY(-400px)';

                    // Задержка перед началом анимации
                    setTimeout(() => {
                        newContainer.style.opacity = '1';
                        newContainer.style.transform = 'translateY(20px)';
                    }, 100);
                }
            } else {
                // Если главный контейнер снова видим, удаляем новый контейнер с анимацией
                if (newContainer) {
                    newContainer.style.opacity = '0';
                    newContainer.style.transform = 'translateY(-100px)';

                    setTimeout(() => {
                        newContainer.remove();
                        newContainer = null;
                    }, 500);
                }
            }
        });
    });

    observer.observe(container);
}
document.addEventListener('turbo:load', function () {
    getCommandBar()
});
getCommandBar()
