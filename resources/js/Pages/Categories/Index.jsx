import React, { useState } from "react";
import Helmet from "react-helmet";
import { InertiaLink, usePage } from "@inertiajs/inertia-react";
import { BaseLayout as Layout } from "@/Components/Layouts";
import { Tree } from "antd";
import "antd/dist/antd.css";
import { Inertia } from "@inertiajs/inertia";
import { loop } from "@/utils";
import { ArrowDownOutlined } from '@ant-design/icons';
import "./styles.css";


export default () => {
  const { categories } = usePage().props;
  const [data, setData] = useState(categories);
  const [expandedKeys, setExpandedKeys] = useState([]);

  const onDrop = (info, categories, setCategories) => {
    const dropKey = info.node.key;
    const dragKey = info.dragNode.key;
    const dropPos = info.node.pos.split("-");
    const dropPosition =
      info.dropPosition - Number(dropPos[dropPos.length - 1]);

    const data = [...categories];

    // Find dragObject
    let dragObj;
    loop(data, dragKey, (item, index, arr) => {
      arr.splice(index, 1);
      dragObj = item;
    });

    if (!info.dropToGap) {
      // Drop on the content
      loop(data, dropKey, (item) => {
        item.children = item.children || [];
        // where to insert
        item.children.unshift(dragObj);
      });
    } else if (
      (info.node.props.children || []).length > 0 && // Has children
      info.node.props.expanded && // Is expanded
      dropPosition === 1 // On the bottom gap
    ) {
      loop(data, dropKey, (item) => {
        item.children = item.children || [];
        // where to insert
        item.children.unshift(dragObj);
      });
    } else {
      let ar, i;
      loop(data, dropKey, (item, index, arr) => {
        ar = arr, i = index;
      });
      if (dropPosition === -1) {
        ar.splice(i, 0, dragObj);
      } else {
        ar.splice(i + 1, 0, dragObj);
      }
    }

    setCategories(data)
    // TODO: Save the new category structure (order is not kept atm)
    Inertia.post(route('categories.updateTree'), data)
  };

  // TODO: WIP
  const onExpand = (expandedKeys, info, data, setExpandedKeys) => {
    const node = info.node; // Toggled node
    const expanded = info.expanded; // Was it expanded?

    // Keep opened node + parent nodes, collapse others

    let keepOpen = [];
    keepOpen.push(node.key)
    expandedKeys.reverse().map((key, index, arr) => {
      let item = null
      // Find the current item recursively
      loop(data, key, (it) => {
        item = it
      })
      if (item && item.parent_id) { keepOpen.push(item.parent_id); }
    });

    setExpandedKeys(keepOpen)

  }

  return (
    <Layout>
      <div className="w-3/12">
        <Helmet title="Categories" />
        <h1 className="mb-8 text-3xl font-bold">Categories</h1>
        <Tree
          treeData={data}
          draggable
          blockNode
          switcherIcon={<ArrowDownOutlined style={{fontSize: 16}} />}
          onExpand={(keys, info) => onExpand(keys, info, data, setExpandedKeys)}
          onDrop={(info) => onDrop(info, data, setData)}
        />
      </div>
    </Layout>
  );
};
