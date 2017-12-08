<?php
/**
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * Copyright (c) 2017 (original work) Open Assessment Technologies SA (under the project TAO-PRODUCT);
 *
 */

namespace oat\taoTaskQueue\scripts\install;

use oat\oatbox\extension\InstallAction;
use oat\taoTaskQueue\model\Queue;
use oat\taoTaskQueue\model\QueueBroker\InMemoryQueueBroker;
use oat\taoTaskQueue\model\QueueDispatcher;
use oat\taoTaskQueue\model\QueueDispatcherInterface;
use oat\taoTaskQueue\model\TaskLogInterface;
use oat\taoTaskQueue\model\TaskSelector\WeightStrategy;

/**
 * Install Action to register task queue service
 *
 * @author Gyula Szucs <gyula@taotesting.com>
 */
class RegisterTaskQueueService extends InstallAction
{
    public function __invoke($params)
    {
        $queueService = new QueueDispatcher([
            QueueDispatcherInterface::OPTION_QUEUES       => [
                new Queue('queue', new InMemoryQueueBroker())
            ],
            QueueDispatcherInterface::OPTION_TASK_LOG     => TaskLogInterface::SERVICE_ID,
            QueueDispatcherInterface::OPTION_TASK_TO_QUEUE_ASSOCIATIONS => [],
            QueueDispatcherInterface::OPTION_TASK_SELECTOR_STRATEGY => new WeightStrategy()
        ]);

        $this->registerService(QueueDispatcherInterface::SERVICE_ID, $queueService);

        return \common_report_Report::createSuccess('Task Queue service successfully registered.');
    }
}