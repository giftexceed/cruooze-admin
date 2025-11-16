<style>
   body {
    max-width: 100%;
    margin: 0 auto;
    height: 100%;
    background: var(--1, #FFF);
}
</style>
<body>
    <?php $address = urlencode($data['event_location']);
   ?>
<iframe src="https://www.google.com/maps/embed/v1/place?q=<?php echo $address;?>&key=AIzaSyCIAy-MdXb1vzK83MDqCDu8frhEPe2OeVE&zoom=20" style="width: 100%; height: 100%;  style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>


</body>