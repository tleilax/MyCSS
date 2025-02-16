<?php

class MarketController extends PluginController
{

    public function index_action()
    {
        Navigation::activateItem('/profile/settings/mycss');
        $this->stylesheets = MycssStylesheet::findBySQL("`public` = '1' ORDER BY `title` ASC");
    }

    public function use_action($stylesheet_id)
    {
        $this->stylesheet = MycssStylesheet::find($stylesheet_id);
        if (!$this->stylesheet['public']) {
            throw new AccessDeniedException();
        }
        $stylesheet = new MycssStylesheet();
        $stylesheet->setData($this->stylesheet->toRawArray());
        $stylesheet->setId($stylesheet->getNewId());
        $stylesheet['active'] = '1';
        $stylesheet['public'] = '0';
        $stylesheet['range_type'] = 'user';
        $stylesheet['range_id'] = User::findCurrent()->id;
        $stylesheet['title'] = $stylesheet['title']._(" (Kopie)");
        $stylesheet->store();
        PageLayout::postSuccess(_('Design wurde kopiert.'));
        $this->redirect('styles/index');
    }
}
