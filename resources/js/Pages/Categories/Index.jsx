import React, { useState } from "react";
import Helmet from "react-helmet";
import { InertiaLink, usePage } from "@inertiajs/inertia-react";
import { BaseLayout as Layout } from "@/Components/Layouts";
import { Tree } from "antd";
import "antd/dist/antd.css";


export default () => {
  const { categories } = usePage().props;
  const [data, setData] = useState(categories);

  const onDrop = (info, categories, setCategories) => {
    const dropKey = info.node.key;
    const dragKey = info.dragNode.key;
    const dropPos = info.node.pos.split("-");
    console.log(info.node)
    const dropPosition =
      info.dropPosition - Number(dropPos[dropPos.length - 1]);

    const loop = (data, key, callback) => {
      for (let i = 0; i < data.length; i++) {
        if (data[i].key === key) {
          return callback(data[i], i, data);
        }
        if (data[i].children) {
          loop(data[i].children, key, callback);
        }
      }
    };
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
      let ar;
      let i;
      loop(data, dropKey, (item, index, arr) => {
        ar = arr;
        i = index;
      });
      if (dropPosition === -1) {
        ar.splice(i, 0, dragObj);
      } else {
        ar.splice(i + 1, 0, dragObj);
      }
    }

    setCategories(data)
  };

  return (
    <Layout>
      <div>
        <Helmet title="Categories" />
        <Tree
          className="draggable-tree"
          treeData={data}
          draggable
          blockNode
          onDrop={(info) => onDrop(info, data, setData)}
        />
        <h1 className="mb-8 text-3xl font-bold">Categories</h1>
        <div className="overflow-x-auto bg-white rounded shadow">
          <table className="w-full whitespace-no-wrap">
            <thead>
              <tr className="font-bold text-left">
                <th className="px-6 pt-5 pb-4">Title</th>
              </tr>
            </thead>
            <tbody>
              {data.map(({ id, title }) => (
                <tr
                  key={id}
                  className="hover:bg-gray-100 focus-within:bg-gray-100"
                >
                  <td className="border-t">
                    <InertiaLink
                      href={route("categories.edit", id)}
                      className="flex items-center px-6 py-4 focus:text-indigo-700"
                      title={`Edit category ${title}`}
                    >
                      {title}
                    </InertiaLink>
                  </td>
                </tr>
              ))}
              {data.length === 0 && (
                <tr>
                  <td className="px-6 py-4 border-t" colSpan="3">
                    No categories found.
                  </td>
                </tr>
              )}
            </tbody>
          </table>
        </div>
      </div>
    </Layout>
  );
};
