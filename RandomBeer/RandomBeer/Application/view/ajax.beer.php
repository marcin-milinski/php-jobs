<dl class="row">
    <dt class="col-md-3">Beer name:</dt>
    <dd class="col-md-9" id="beer-name"><?php echo htmlspecialchars($beer['name']); ?></dd>

    <dt class="col-md-3">Beer description:</dt>
    <dd class="col-md-9" id="beer-desc"><?php echo htmlspecialchars($beer['description']); ?></dd>

    <dt class="col-md-3">Beer ABV:</dt>
    <dd class="col-md-9"><span id="beer-abv"><?php echo htmlspecialchars($beer['abv']); ?></span> &percnt;</dd>

    <dt class="col-md-3">Brewery location:</dt>
    <dd class="col-md-9" id="beer-location"><?php echo htmlspecialchars($beer['brewery_location']); ?></dd>
<dl>
