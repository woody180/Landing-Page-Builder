This **landing page builder** is created with [toolx php framework](https://github.com/woody180/toolx_php_framework_)

# Basic instructions

# Make content editable

There is the helper file in framework, inside the helpers/tinyeditor directory. There are few functions. In order to make content simply editable follow instruction below.

**Landing page builder** uses [tinyeditor](https://github.com/woody180/tinyeditor) tool. Parent and child classes provided in the example below comes form [tinyeditor](https://github.com/woody180/tinyeditor). They simply makes content editable.

```
<div class="<?= tinyClass('parent') ?>">
    <div class="<?= tinyClass('child') ?>">
        <p>Change the text because it is editable. ;)</p>
    </div>
</div>
```

# Save content
As you have seen in the above section there are parent and child elements and corresponding classes ***tinyClass('parent) & tinyClass('child')***. In the parent element, we need to provide a few attributes. The first is an **alias** attribute, which determines the path where content is going to be saved in the database.

First word in alias attribute before dot mark is the table name. Second is the ID of the record, Third (body) is the row. Farther ("content" at this point) is the json key. 

It can be determined without **JSON**. Just provide table name, id of the record and row. That can be it!

All content laid in the child element going to be saved in database.

Follow this  [**link of tinyeditor**](https://github.com/woody180/tinyeditor) to find farther instructions.

```
<div class="<?= tinyClass('parent') ?>" alias="section.12.body.content">
    <div class="<?= tinyClass('child') ?>">
        <p>Change the text because it is editable. ;)</p>
    </div>
</div>
```

# Make sections editable

Consider that you want to change the color or background image of the section. You may want to change the text color and so on.
To do so, first we need to add an editable button, which triggers the sidebar from where we are going to be able to modify stuff. Just add **ld-set-editable-icon** class.

After eddit class we also need to add **ID** of the section in attribute manner - **data-section-id="12"**.

For a better understanding of what is happening here, let's look at the database.

### How to be linked with section.
Let's break down what is happening here.
We can grab sections by their names. So every section has its own unique name, from which we are getting them.

In the example below, there are few attributes. In the **data-background-color** attribute we can see the **$section** varaible. It comes from **app/Controllers/HomeController.php** controller. The **stats** chaning method is the **NAME** of the section and finally **color** chaning method is the name of the ROW.

**data-bg** attribute takes helper function. First argument of the **background()** function is the **$section** variable which comes form **app/Controllers/HomeController.php** controller and the second one is the name of the section.

```
<section 
    data-section-id="<?= $section->id ?>" 
    class="ld-set-editable-icon"
    data-background-color="<?= $section->stats->color ?>" 
    data-text-color="<?= $section->stats->text_color ?>"
    data-bg="<?= background($section, 'stats') ?>" >
>

    <div class="<?= tinyClass('parent') ?>" alias="section.12.body.content">
        <div class="<?= tinyClass('child') ?>">
            <p>Change the text because it is editable. ;)</p>
        </div>
    </div>
</section>
```