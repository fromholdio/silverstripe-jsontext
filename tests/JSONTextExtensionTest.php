<?php

/**
 * @package silverstripe-jsontext
 * @subpackage fields
 * @author Russell Michell <russ@theruss.com>
 */

use PhpTek\JSONText\Exception\JSONTextException;
use SilverStripe\Dev\FunctionalTest;
use SilverStripe\Dev\TestOnly;
use SilverStripe\Core\Config\Config;
use SilverStripe\Security\Member;

class JSONTextExtensionTest extends FunctionalTest
{
    /**
     * @var string
     */
    protected static $fixture_file = 'jsontext/tests/fixtures/yml/JSONTextExtension.yml';
    
    /**
     * Is an exception thrown when no POSTed vars are available for
     * non DB-backed fields declared on a SiteTree class?
     */
    public function testExceptionThrownOnBeforeWrite()
    {
        $member = $this->objFromFixture(Member::class, 'admin');
        
        $fixture = Page::create([
            'ID' => 44,
            'Title' => 'Dummy',
            'ParentID' => 0
        ]);
        
        $member->logIn();
        $fixture->config()->update('db', ['MyJSON' => 'JSONText']);
        $fixture->config()->update('json_field_map', ['MyJSON' => ['FooField']]);
        $fixture->write();
        
        // Submit a CMS POST request _without_ JSON data
        $this->setExpectedException(JSONTextException::class);
        $this->post('admin/pages/edit/EditForm/44/', [
            'ParentID' => '0',
            'action_save' => 'Saved',
            'ID' => '44',
        ]);
    }
}
