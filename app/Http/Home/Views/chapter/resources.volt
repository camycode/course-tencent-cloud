{% extends 'templates/layer.volt' %}

{% block content %}

    <table class="kg-table layui-table">
        <tr>
            <th>名称</th>
            <th>大小</th>
            <th width="15%">操作</th>
        </tr>
        {% for item in items %}
            <tr>
                <td>{{ item.name }}</td>
                <td>{{ item.size|human_size }}</td>
                <td><a class="layui-btn layui-btn-sm" href="{{ item.url }}" target="_blank">下载</a></td>
            </tr>
        {% endfor %}
    </table>

{% endblock %}

{% block inline_js %}

    <script>
        var index = parent.layer.getFrameIndex(window.name);
        parent.layer.iframeAuto(index);
    </script>

{% endblock %}