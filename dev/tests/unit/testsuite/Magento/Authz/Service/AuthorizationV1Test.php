<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @copyright   Copyright (c) 2014 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Magento\Authz\Service;

use Magento\Authz\Model\UserIdentifier;
use Magento\User\Model\Role;

class AuthorizationV1Test extends \PHPUnit_Framework_TestCase
{
    /** @var AuthorizationV1 */
    protected $_authzService;

    protected function setUp()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|Role $roleMock */
        $roleMock = $this->getMock('Magento\User\Model\Role', array('load', 'delete', '__wakeup'), array(), '', false);
        $roleMock->expects($this->any())->method('load')->will($this->returnSelf());
        $roleMock->expects($this->any())->method('delete')->will($this->returnSelf());

        /** @var \PHPUnit_Framework_MockObject_MockObject|\Magento\User\Model\RoleFactory $roleFactoryMock */
        $roleFactoryMock = $this->getMock('Magento\User\Model\RoleFactory', array('create'), array(), '', false);
        $roleFactoryMock->expects($this->any())->method('create')->will($this->returnValue($roleMock));

        $this->_authzService = new AuthorizationV1(
            $this->getMock('Magento\Framework\Acl\Builder', array(), array(), '', false),
            $this->getMock('Magento\Authz\Model\UserIdentifier', array(), array(), '', false),
            $roleFactoryMock,
            $this->getMock('Magento\User\Model\Resource\Role\CollectionFactory', array(), array(), '', false),
            $this->getMock('Magento\User\Model\RulesFactory', array(), array(), '', false),
            $this->getMock('Magento\User\Model\Resource\Rules\CollectionFactory', array(), array(), '', false),
            $this->getMock('Magento\Framework\Logger', array(), array(), '', false),
            $this->getMock('Magento\Framework\Acl\RootResource', array(), array(), '', false)
        );
    }

    public function testRemovePermissions()
    {
        $this->_authzService->removePermissions($this->_getUserIdentifierMock(UserIdentifier::USER_TYPE_INTEGRATION));
    }

    /**
     * @expectedException \Magento\Webapi\ServiceException
     */
    public function testRemovePermissionsException()
    {
        // Wrong user identifier type
        $this->_authzService->removePermissions($this->_getUserIdentifierMock(UserIdentifier::USER_TYPE_ADMIN));
    }

    /**
     * @param string $getUserTypeValue
     * @return UserIdentifier|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function _getUserIdentifierMock($getUserTypeValue)
    {
        /** @var UserIdentifier|\PHPUnit_Framework_MockObject_MockObject  $userIdentiferMock */
        $userIdentiferMock = $this->getMock(
            'Magento\Authz\Model\UserIdentifier',
            array('getUserType', 'getUserId'),
            array(),
            '',
            false
        );

        $userIdentiferMock->expects($this->any())->method('getUserType')->will($this->returnValue($getUserTypeValue));

        return $userIdentiferMock;
    }
}
