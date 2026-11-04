<div class="event-card flex flex-col h-full">
    <a href="<?= $post->getEventRegisterLink() ?>" target="_blank" class="relative aspect-video overflow-hidden mb-4 block">
        <img src="<?= $post->getImageUrl('large'); ?>" alt="<?= $post->getTitle(); ?>" class="w-full h-full object-cover object-center">
    </a>
    <div class="flex flex-col flex-grow">
        <h3 class="display-2 !font-normal mb-3">
            <a href="<?= $post->getEventRegisterLink(); ?>" target="_blank" class="hover:underline"><?= $post->getTitle(); ?></a>
        </h3>

        <div class="flex flex-col gap-[2px] mb-5">
            <?php if ($post->getEventDate()): ?>
                <div class="flex items-center gap-2">
                    <div class="w-5 h-5 flex items-center justify-center">
                        <img src="<?= get_image('calendar.svg'); ?>" alt="Calendar">
                    </div>
                    <span class="body-2"><?= $post->getEventDate(); ?></span>
                </div>
            <?php endif; ?>
            <?php if ($post->getEventTime()): ?>
                <div class="flex items-center gap-2">
                    <div class="w-5 h-5 flex items-center justify-center">
                        <img src="<?= get_image('time.svg'); ?>" alt="Time">
                    </div>
                    <span class="body-2"><?= $post->getEventTime(); ?></span>
                </div>
            <?php endif; ?>
            <?php if ($post->getEventLocation()): ?>
                <div class="flex items-center gap-2">
                    <div class="w-5 h-5 flex items-center justify-center">
                        <img src="<?= get_image('pin.svg'); ?>" alt="Pin">
                    </div>
                    <span class="body-2"><?= $post->getEventLocation(); ?></span>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($post->getEventRegisterLink()): ?>
            <div class="mt-auto">
                <a href="<?= $post->getEventRegisterLink(); ?>" target="_blank" class="ui-link !inline-flex items-center gap-[5px]">
                    <span><?= get_field('link_text', $post->getId()) ?: 'Register to attend'; ?></span>
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2.5 13.5L13.5 2.5M13.5 2.5H4.5M13.5 2.5V11.5" stroke="black" stroke-width="1.5" stroke-linecap="square"/>
                    </svg>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>