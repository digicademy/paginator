# Paginator

Improved Extbase/Fluid pagination widget sporting stable page ranges, single view pagination and easy handling of request arguments.
Pagination links are POST and GET aware, no more complicated widget link/ widget context stuff. Uses plain f:link view helper in
widget templates.

# Examples

## List view

    {namespace paginate = ADWLM\Paginator\ViewHelpers}

    <paginate:widget.list objects="{myObjects}" as="myPaginatedObjects" arguments="{paginate:CleanArguments(arguments : arguments)}" configuration="{itemsPerPage: 10, maxPageNumberElements: 10, insertAbove: 1, insertBelow: 1, showCount: 1}">

        <ul>
        <f:for each="{myPaginatedObjects}" as="myObject" iteration="iterator">
            <f:alias map="{currentItem : '{paginate:ItemCount(currentPage : arguments.currentPage, currentItem : iterator.cycle, itemsPerPage : 10)}'}">

                <li>
                    <f:link.action action="myAction" arguments="{object : myObject.uid, currentItem : currentItem, currentPage : arguments.currentPage}">
                            {currentItem}) {myObject.name}
                    </f:link.action>
                </li>

            </f:alias>
        </f:for>
        <ul>

    </paginate:widget.list>

## Single view

    {namespace paginate = ADWLM\Paginator\ViewHelpers}

    <paginate:widget.single objects="{myObjects}" arguments="{paginate:CleanArguments(arguments : arguments)}" configuration="{itemsPerPage : 50, showCount : 0, objectArgumentName : 'object'}" />